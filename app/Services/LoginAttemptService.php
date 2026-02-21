<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Lockout progresivo por RUN + IP.
 * Claves: run|ip (intentos por RUN+IP) y run (bloqueo por RUN).
 * Escalado: 5 fallos → 5 min, 10 → 30 min, 15 → 24 h.
 */
class LoginAttemptService
{
    private const WINDOW_MINUTES = 15;
    private const KEY_PREFIX_ATTEMPTS = 'login_attempts:';
    private const KEY_PREFIX_LOCKOUT = 'login_lockout:';
    private const THRESHOLDS = [
        5 => 5,      // 5 intentos → bloqueo 5 min
        10 => 30,    // 10 intentos → bloqueo 30 min
        15 => 24 * 60, // 15 intentos → bloqueo 24 h (minutos)
    ];

    public function isLockedOut(string $run, ?string $ip = null): bool
    {
        $lockKey = self::KEY_PREFIX_LOCKOUT . $run;
        return Cache::has($lockKey);
    }

    public function getLockoutMinutesRemaining(string $run): int
    {
        $lockKey = self::KEY_PREFIX_LOCKOUT . $run;
        $until = Cache::get($lockKey);
        if (!$until) {
            return 0;
        }
        $remaining = (int) ceil(($until - time()) / 60);
        return max(0, $remaining);
    }

    /**
     * Registra un intento fallido. Retorna true si se aplicó lockout (y se audita).
     */
    public function recordFailedAttempt(string $run, Request $request): bool
    {
        $ip = $request->ip();
        $ua = $request->userAgent();
        $compositeKey = self::KEY_PREFIX_ATTEMPTS . $run . '|' . $ip;
        $runKey = self::KEY_PREFIX_ATTEMPTS . 'run:' . $run;

        $attempts = (int) Cache::get($compositeKey, 0);
        $attemptsRun = (int) Cache::get($runKey, 0);
        $attempts++;
        $attemptsRun++;

        Cache::put($compositeKey, $attempts, now()->addMinutes(self::WINDOW_MINUTES));
        Cache::put($runKey, $attemptsRun, now()->addMinutes(self::WINDOW_MINUTES));

        $lockoutMinutes = null;
        foreach (self::THRESHOLDS as $threshold => $minutes) {
            if ($attemptsRun >= $threshold) {
                $lockoutMinutes = $minutes;
            }
        }

        if ($lockoutMinutes !== null) {
            $lockKey = self::KEY_PREFIX_LOCKOUT . $run;
            Cache::put($lockKey, time() + $lockoutMinutes * 60, now()->addMinutes($lockoutMinutes + 1));
            AuditoriaService::registrar('login_lockout', 'usuarios', null, null, null, [
                'run' => $run,
                'ip' => $ip,
                'user_agent' => $ua,
                'intentos_run' => $attemptsRun,
                'nivel_bloqueo_minutos' => $lockoutMinutes,
            ]);
            Log::channel('security')->warning('login_lockout', [
                'run' => $run,
                'ip' => $ip,
                'user_agent' => $ua,
                'lockout_minutes' => $lockoutMinutes,
            ]);
            return true;
        }

        return false;
    }

    public function clearAttempts(string $run): void
    {
        $runKey = self::KEY_PREFIX_ATTEMPTS . 'run:' . $run;
        Cache::forget($runKey);
        Cache::forget(self::KEY_PREFIX_LOCKOUT . $run);
        // No podemos borrar todas las claves run|ip sin conocer las IPs; el lockout por run ya se limpia.
    }

    /**
     * Limpia intentos tras login exitoso (opcional, para no castigar al usuario legítimo).
     */
    public function clearAttemptsForRun(string $run): void
    {
        $this->clearAttempts($run);
    }
}
