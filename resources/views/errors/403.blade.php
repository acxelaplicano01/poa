<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>403 - Acceso denegado | {{ config('app.name', 'Sistema') }}</title>

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
                rgba(245, 158, 11, 0.03) 0%, 
                rgba(217, 119, 6, 0.03) 25%, 
                rgba(180, 83, 9, 0.03) 51%, 
                rgba(245, 158, 11, 0.03) 100%
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
            color: #92400e;
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
            background: linear-gradient(45deg, #f59e0b, #d97706, #b45309);
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
        <div class="error-number-bg">403</div>
        
        <div class="max-w-2xl w-full relative z-10 card-border bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-3 w-3 rounded-full bg-red-500 mr-2"></div>
                    <div class="h-3 w-3 rounded-full bg-yellow-500 mr-2"></div>
                    <div class="h-3 w-3 rounded-full bg-green-500"></div>
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">access-denied.html</div>
            </div>
            
            <div class="px-6 md:px-10 py-10">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="mb-6 md:mb-0 md:mr-8">
                        <svg class="h-20 w-20 text-amber-500 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    
                    <div class="text-center md:text-left">
                        <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-white mb-2">Acceso denegado</h1>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">No tienes permisos suficientes para acceder a esta página o recurso. Si crees que esto es un error, contacta con el administrador del sistema.</p>
                        
                        <!-- Detalles técnicos -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 border border-gray-200 dark:border-gray-700 rounded-md p-4 mb-6">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-amber-500 mt-0.5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Información de permisos</h3>
                                    <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                                        Código de error: 403 | Recurso: {{ request()->path() }} | 
                                        @auth
                                            Usuario: {{ auth()->user()->email }}
                                        @else
                                            Usuario: No autenticado
                                        @endauth
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap justify-center md:justify-start gap-3">
                            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <span class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Volver atrás
                                </span>
                            </a>
                            @guest
                            <a href="{{ route('login') }}" class="px-4 py-2 bg-amber-600 text-white rounded-md shadow-sm hover:bg-amber-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                <span class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    Iniciar sesión
                                </span>
                            </a>
                            @endguest
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-gray-900 dark:bg-gray-600 text-white rounded-md shadow-sm hover:bg-gray-800 dark:hover:bg-gray-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                <span class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Inicio
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 sm:mb-0">
                    {{ config('app.name', 'POA') }} &copy; {{ date('Y') }}. Todos los derechos reservados.
                </p>
                <div class="flex space-x-4">
                    <a href="https://chat.whatsapp.com/CnEA4qNlOBoLK1Hh8NKsKI" title="Soporte Técnico" target="_blank" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <span class="sr-only">Soporte Técnico</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92c-.5.51-.86.97-1.04 1.69-.08.32-.13.68-.13 1.14h-2v-.5c0-.46.08-.9.22-1.31.2-.58.53-1.1.95-1.52l1.24-1.26c.46-.44.68-1.1.55-1.8-.13-.72-.69-1.33-1.39-1.53-1.11-.31-2.14.32-2.47 1.27-.12.35-.47.56-.83.56-.5 0-.9-.43-.8-.92.34-1.77 1.89-3.1 3.81-3.1 1.77 0 3.36 1.2 3.81 2.96.22.88.1 1.75-.29 2.55-.32.57-.78 1.08-1.3 1.45z"/>
                        </svg>
                    </a>
                    <a href="{{route('policy.show')}}" title="Política de Privacidad" target="_blank" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <span class="sr-only">Política de Privacidad</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>