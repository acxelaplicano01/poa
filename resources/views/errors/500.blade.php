<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>500 - Error del servidor | {{ config('app.name', 'POA') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('Logo/icon-72x72.png') }}" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .animated-gradient {
            background-size: 400% 400%;
            animation: gradient-shift 15s ease infinite;
            background-image: linear-gradient(-45deg, 
                rgba(239, 68, 68, 0.03) 0%, 
                rgba(185, 28, 28, 0.03) 25%, 
                rgba(220, 38, 38, 0.03) 51%, 
                rgba(239, 68, 68, 0.03) 100%
            );
        }
        
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .error-number-bg {
            position: absolute;
            opacity: 0.05;
            font-size: 30vw;
            font-weight: 700;
            line-height: 1;
            letter-spacing: -0.05em;
            user-select: none;
            pointer-events: none;
            color: #9f1239;
        }
        
        .card-border {
            position: relative;
        }
        
        .card-border::after {
            content: "";
            position: absolute;
            top: -1px;
            left: -1px;
            right: -1px;
            bottom: -1px;
            border-radius: 0.5rem;
            padding: 1px;
            background: linear-gradient(45deg, #ef4444, #b91c1c, #dc2626);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0.5;
            z-index: -1;
        }
        
        .error-status {
            height: 12px;
            width: 12px;
            border-radius: 50%;
            display: inline-block;
            background-color: #ef4444;
            position: relative;
        }
        
        .error-status::after {
            content: "";
            position: absolute;
            height: 100%;
            width: 100%;
            top: 0;
            left: 0;
            border-radius: 50%;
            background-color: #ef4444;
            opacity: 0.7;
            animation: pulse 2s ease-out infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.7;
            }
            70% {
                transform: scale(2.5);
                opacity: 0;
            }
            100% {
                transform: scale(2.5);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="h-full animated-gradient bg-white dark:bg-gray-900">
    <div class="min-h-screen flex items-center justify-center relative p-4">
        <!-- Error number background -->
        <div class="error-number-bg">500</div>
        
        <div class="max-w-2xl w-full relative z-10 card-border bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-3 w-3 rounded-full bg-red-500 mr-2"></div>
                    <div class="h-3 w-3 rounded-full bg-yellow-500 mr-2"></div>
                    <div class="h-3 w-3 rounded-full bg-green-500"></div>
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">server-error.html</div>
            </div>
            
            <div class="px-6 md:px-10 py-10">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="mb-6 md:mb-0 md:mr-8">
                        <svg class="h-20 w-20 text-red-500 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    
                    <div class="text-center md:text-left">
                        <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-white mb-2">Error interno del servidor</h1>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">Ha ocurrido un error inesperado en nuestros servidores. Nuestro equipo técnico ha sido notificado y está trabajando para resolverlo.</p>
                        
                        <!-- Detalles técnicos -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 border border-gray-200 dark:border-gray-700 rounded-md p-4 mb-6">
                            <div class="flex items-start">
                                <div class="error-status mt-1 mr-3"></div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Estado del sistema</h3>
                                    <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                                        Código de error: 500 | Timestamp: {{ now()->format('Y-m-d H:i:s') }} | 
                                        ID de incidente: {{ substr(md5(time()), 0, 8) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap justify-center md:justify-start gap-3">
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition focus:outline-none focus:ring-2 focus:ring-red-500">
                                <span class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Ir a la página principal
                                </span>
                            </a>
                            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-red-600 text-white rounded-md shadow-sm hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <span class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Reintentar
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 sm:mb-0">
                    {{ config('app.name', 'Sistema') }} &copy; {{ date('Y') }}. Todos los derechos reservados.
                </p>
                <div class="flex space-x-4">
                    <a href="https://chat.whatsapp.com/CnEA4qNlOBoLK1Hh8NKsKI" title="Soporte Técnico" target="_blank" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <span class="sr-only">Soporte Técnico</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h4v3c0 .6.4 1 1 1h.5c.2 0 .5-.1.7-.3l3.7-3.7H20c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H13.9l-2.5 2.5V16H4V4h16v12z"/>
                        </svg>
                    </a>
                    <a href="#" title="Estado del Sistema" target="_blank" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <span class="sr-only">Estado del Sistema</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>