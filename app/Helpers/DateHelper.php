<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Formatear fecha en formato chileno (DD-MM-YYYY)
     */
    public static function formatChile($date, $format = 'd-m-Y')
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        return $date->format($format);
    }
    
    /**
     * Obtener fecha actual en formato chileno
     */
    public static function todayChile($format = 'd-m-Y')
    {
        return Carbon::now()->format($format);
    }
    
    /**
     * Formatear fecha con hora en formato chileno
     */
    public static function formatChileWithTime($date, $format = 'd-m-Y H:i')
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        return $date->format($format);
    }
    
    /**
     * Obtener nombre del mes en español
     */
    public static function monthName($date = null)
    {
        if ($date === null) {
            $date = Carbon::now();
        } elseif (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        return $date->format('F');
    }
    
    /**
     * Obtener año y mes en español
     */
    public static function yearMonth($date = null)
    {
        if ($date === null) {
            $date = Carbon::now();
        } elseif (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        return $date->format('F Y');
    }
}

