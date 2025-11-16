<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Proveedor de IA
    |--------------------------------------------------------------------------
    |
    | Define qué proveedor de IA utilizar para generar actividades.
    | Opciones disponibles: 'openai', 'gemini'
    |
    */

    'provider' => env('IA_PROVIDER', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | API Key de Gemini
    |--------------------------------------------------------------------------
    |
    | Tu clave de API de Google Gemini.
    | Obtén una en: https://makersuite.google.com/app/apikey
    |
    */

    'gemini_api_key' => env('GEMINI_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Configuración de Modelos
    |--------------------------------------------------------------------------
    |
    | Configuración específica para cada proveedor
    |
    */

    'models' => [
        'openai' => [
            'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
            'temperature' => 0.7,
            'max_tokens' => 600,
        ],
        'gemini' => [
            'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
            'temperature' => 0.7,
            'max_tokens' => 800,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Throttling
    |--------------------------------------------------------------------------
    |
    | Tiempo mínimo en segundos entre solicitudes por usuario
    |
    */

    'throttle_seconds' => env('IA_THROTTLE_SECONDS', 30),

];
