<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Módulos extras / pagados
    |--------------------------------------------------------------------------
    |
    | Listado de módulos con indicador activado/desactivado. La interfaz
    | comprueba module_enabled('clave') y no muestra menús ni enlaces
    | cuando el módulo está desactivado. Cambiar 'enabled' a true/false
    | para ofrecer o no el módulo sin tocar código ni cliente.
    |
    */

    'modules' => [

        'control_acceso' => [
            'enabled' => true,
            'label' => 'Control de acceso',
            'description' => 'Ingresos/salidas, blacklist, escáner QR y entrada manual.',
        ],

        'documentos_guardias' => [
            'enabled' => false,
            'label' => 'Documentos de guardias',
            'description' => 'Documentos personales y aprobación por supervisión.',
        ],

        'rondas_qr' => [
            'enabled' => true,
            'label' => 'Rondas QR',
            'description' => 'Puntos de ronda y escaneos QR.',
        ],

        'reportes_diarios' => [
            'enabled' => false,
            'label' => 'Reportes diarios',
            'description' => 'Vista y exportación de reportes diarios (admin).',
        ],

        'calculo_sueldos' => [
            'enabled' => false,
            'label' => 'Cálculo de sueldos',
            'description' => 'Días trabajados y cálculo de sueldos.',
        ],

    ],

];
