<x-guest-layout>
    <div
        class="min-h-screen h-screen flex flex-col justify-center items-center px-4 py-12 sm:px-6 lg:px-8 bg-blue-100 dark:bg-zinc-900">
        <!-- Contenedor centrado con sombra -->
        <div
            class="w-full max-w-5xl flex flex-col md:flex-row bg-white dark:bg-zinc-800 rounded-lg shadow-xl overflow-hidden">
            <!-- Columna del formulario -->
            <div class="w-full md:w-1/2 p-8 lg:p-12">
                <div class="max-w-md w-full mx-auto">
                    <div class="text-center md:text-left mb-8">
                        <h2 class=" text-2xl font-bold text-zinc-900 dark:text-white">
                            {{ __('Bienvenido de vuelta') }}
                        </h2>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ __('Inicia sesión en tu cuenta para continuar') }}
                        </p>
                    </div>

                    <!-- Mensajes de validación -->
                    <x-validation-errors class="mb-4" />

                    @session('status')
                        <div
                            class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 p-3 rounded-md">
                            {{ $value }}
                        </div>
                    @endsession

                    <!-- Botones de inicio de sesión social 
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <a href="#" class="flex justify-center items-center py-2 px-4 border border-zinc-300 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-600 transition-colors">
                            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" width="24" height="24">
                                <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">
                                    <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z"/>
                                    <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z"/>
                                    <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z"/>
                                    <path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z"/>
                                </g>
                            </svg>
                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Google</span>
                        </a>
                        <a href="#" class="flex justify-center items-center py-2 px-4 border border-zinc-300 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-600 transition-colors">
                            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.6 13.8C17.4 14.3 17.1 14.7 16.9 15.2C16.5 16 16.1 16.8 15.6 17.6C14.9 18.7 14.3 19.4 13.8 19.8C13 20.3 12.2 20.6 11.3 20.6C10.7 20.6 9.9 20.4 9 20C8.1 19.6 7.3 19.4 6.5 19.4C5.7 19.4 4.8 19.6 3.9 20C3 20.4 2.3 20.6 1.9 20.6C0.9 20.7 0 20.3 -0.9 19.2C-1.4 18.4 -1.9 17.5 -2.3 16.5C-2.8 15.4 -3 14.2 -3 13C-3 11.6 -2.7 10.3 -2.1 9.3C-1.6 8.4 -1 7.7 -0.2 7.1C0.6 6.5 1.4 6.2 2.3 6.2C3.2 6.2 4.1 6.4 5 6.7C5.9 7 6.5 7.2 6.8 7.2C7.1 7.2 7.9 6.9 9.1 6.5C10.3 6 11.3 5.8 12.2 6C13.9 6.2 15.2 6.9 16.1 8.1C14.6 9 13.9 10.3 13.9 11.9C13.9 13.2 14.4 14.3 15.3 15.1C15.7 15.5 16.2 15.8 16.7 16C16.7 15.9 16.7 15.8 16.6 15.7H16.6L16.6 15.7V15.7C16.9 15.1 17.2 14.5 17.6 13.8L17.6 13.8Z" transform="translate(3, 2)"/>
                            </svg>
                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Apple</span>
                        </a>
                    </div> -->

                    <!-- Separador 
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-zinc-300 dark:border-zinc-700"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="px-2 bg-white dark:bg-zinc-800 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ __('O') }}
                            </span>
                        </div>
                    </div> -->

                    <!-- Formulario de login -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-label for="email" value="{{ __('Usuario o número de empleado') }}"
                                class="text-zinc-700 dark:text-zinc-300" />
                            <div class="mt-1">
                                <x-input id="email"
                                    class="block mt-1 w-full border-zinc-300 dark:border-zinc-700 dark:bg-zinc-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    type="email" name="email" :value="old('email')" required autofocus
                                    autocomplete="username" placeholder="juan.perez o 19898" />
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between">
                                <x-label for="password" value="{{ __('Contraseña') }}"
                                    class="text-zinc-700 dark:text-zinc-300" />
                                @if (Route::has('password.request'))
                                    <a class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                                        href="{{ route('password.request') }}">
                                        {{ __('¿Olvidaste tu contraseña?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="mt-1">
                                <x-input id="password"
                                    class="block mt-1 w-full border-zinc-300 dark:border-zinc-700 dark:bg-zinc-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    type="password" name="password" required autocomplete="current-password"
                                    placeholder="Ingresa tu contraseña" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="flex items-center">
                                <x-checkbox id="remember_me" name="remember"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-zinc-300 dark:border-zinc-600 rounded" />
                                <span
                                    class="ml-2 text-sm text-zinc-700 dark:text-zinc-300">{{ __('Recordarme') }}</span>
                            </label>
                        </div>

                        <div>
                            <button type="submit" id="login-button"
                                class="w-full flex justify-center items-center py-3 px-4 bg-indigo-600 dark:bg-indigo-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                <span id="button-text">{{ __('Iniciar sesión') }}</span>
                                <span id="button-spinner" class="hidden ml-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const form = document.querySelector('form');
                            const button = document.getElementById('login-button');
                            const buttonText = document.getElementById('button-text');
                            const buttonSpinner = document.getElementById('button-spinner');

                            form.addEventListener('submit', function() {
                                button.disabled = true;
                                buttonText.textContent = 'Iniciando...';
                                buttonSpinner.classList.remove('hidden');
                            });
                        });
                    </script>

                    <!-- Enlace de registro -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ __('¿No tienes una cuenta?') }}
                            <a href="{{ route('register') }}"
                                class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                                {{ __('Regístrate') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Columna con ilustración como fondo -->
            <div class="hidden md:block md:w-1/2 bg-indigo-50 dark:bg-zinc-700 bg-no-repeat bg-cover bg-center"
                style="background-image: url('{{ asset('Logo/cc_bg.webp') }}');">
                <div class="h-full w-full flex items-center justify-center p-8 bg-blue-600/40 dark:bg-zinc-900/60">
                    <div class="max-w-md text-white">
                        <img src="{{ asset('Logo/poav2.webp') }}" alt="Logo POA" class="h-24 w-auto mx-auto mb-4">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie de página opcional -->
        <div class="mt-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
            &copy; {{ date('Y') }} {{ config('app.name', 'POA') }}.
            {{ __('Creado por Ingeniería en Sistemas UNAH Campus Choluteca.') }}
        </div>
    </div>
</x-guest-layout>