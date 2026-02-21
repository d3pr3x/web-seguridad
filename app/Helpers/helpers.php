<?php

if (!function_exists('module_enabled')) {
    /**
     * Comprueba si un módulo extra/pagado está activado.
     * La interfaz debe ocultar menús y enlaces cuando devuelve false.
     *
     * @param  string  $key  Clave del módulo (ej: 'control_acceso', 'documentos_guardias')
     * @return bool
     */
    function module_enabled(string $key): bool
    {
        $module = config("modules.modules.{$key}");

        return isset($module['enabled']) && $module['enabled'] === true;
    }
}

if (!function_exists('module_config')) {
    /**
     * Devuelve la configuración de un módulo (label, description, etc.) o null si no existe.
     *
     * @param  string  $key
     * @return array|null
     */
    function module_config(string $key): ?array
    {
        return config("modules.modules.{$key}");
    }
}

if (!function_exists('empresa_permite_modulo')) {
    /**
     * Comprueba si la empresa tiene habilitado el módulo.
     * Si no se pasa empresa, se usa la del usuario en sesión (sucursal->empresa).
     *
     * @param  string  $key  Clave del módulo
     * @param  \App\Models\Empresa|null  $empresa
     * @return bool
     */
    function empresa_permite_modulo(string $key, $empresa = null): bool
    {
        if ($empresa === null && auth()->check()) {
            $sucursal = auth()->user()->sucursal;
            if ($sucursal) {
                // Evitar conflicto con la columna 'empresa' (string) en sucursales: usar la relación
                $empresa = $sucursal->getRelationValue('empresa') ?? $sucursal->empresa()->first();
            }
        }
        if (! $empresa || ! ($empresa instanceof \App\Models\Empresa)) {
            return true;
        }

        return $empresa->permiteModulo($key);
    }
}

if (!function_exists('module_enabled_for_empresa')) {
    /**
     * Módulo habilitado globalmente Y permitido para la empresa activa.
     *
     * @param  string  $key  Clave del módulo
     * @param  \App\Models\Empresa|null  $empresa
     * @return bool
     */
    function module_enabled_for_empresa(string $key, $empresa = null): bool
    {
        return module_enabled($key) && empresa_permite_modulo($key, $empresa);
    }
}
