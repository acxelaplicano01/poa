<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>404 - Página no encontrada | {{ config('app.name', 'Sistema') }}</title>

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
                rgba(59, 130, 246, 0.03) 0%, 
                rgba(16, 185, 129, 0.03) 25%, 
                rgba(99, 102, 241, 0.03) 51%, 
                rgba(59, 130, 246, 0.03) 100%
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
            color: #1e40af;
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
            background: linear-gradient(45deg, #3b82f6, #10b981, #6366f1);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0.5;
            z-index: -1;
        }
    </style>
</head>
<body class="h-full animated-gradient bg-white dark:bg-gray-900">
    <div class="min-h-screen flex items-center justify-center relative p-4">
        <!-- Error number background -->
        <div class="error-number-bg">404</div>
        
        <div class="max-w-2xl w-full relative z-10 card-border bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-3 w-3 rounded-full bg-red-500 mr-2"></div>
                    <div class="h-3 w-3 rounded-full bg-yellow-500 mr-2"></div>
                    <div class="h-3 w-3 rounded-full bg-green-500"></div>
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">error-404.html</div>
            </div>
            
            <div class="px-6 md:px-10 py-10">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="mb-6 md:mb-0 md:mr-8">
                        <svg class="h-20 w-20 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 14c.5523 0 1-.4477 1-1s-.4477-1-1-1-1 .4477-1 1 .4477 1 1 1z" />
                        </svg>
                    </div>
                    
                    <div class="text-center md:text-left">
                        <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-white mb-2">Recurso no encontrado</h1>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">No pudimos encontrar la página que estás buscando. Es posible que haya sido movida, eliminada o nunca haya existido.</p>
                        
                        <!-- Detalles técnicos -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 border border-gray-200 dark:border-gray-700 rounded-md p-4 mb-6">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-blue-500 mt-0.5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Información técnica</h3>
                                    <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                                        Código de error: 404 | Tiempo: {{ now()->format('Y-m-d H:i:s') }} | 
                                        ID de solicitud: {{ Str::random(8) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap justify-center md:justify-start gap-3">
                            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <span class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Volver atrás
                                </span>
                            </a>
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <span class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Ir a la página principal
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
                            <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 100-16 8 8 0 000 16zm-1-5h2v2h-2v-2zm0-8h2v6h-2V7z"/>
                        </svg>
                    </a>
                    <a href="#" title="Documentación" target="_blank" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <span class="sr-only">Documentación</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 14v4.833A1.166 1.166 0 0 1 16.833 20H5.167A1.167 1.167 0 0 1 4 18.833V7.167A1.166 1.166 0 0 1 5.167 6h4.618m4.447-2H20v5.768m-7.889 2.121 7.778-7.778" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>