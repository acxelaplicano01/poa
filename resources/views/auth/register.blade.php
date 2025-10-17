<x-guest-layout>
    <div
        class="min-h-screen h-screen flex flex-col justify-center items-center px-4 py-12 sm:px-6 lg:px-8 bg-blue-100 dark:bg-zinc-900">
        <!-- Contenedor centrado con sombra -->
        <div
            class="w-full max-w-5xl flex flex-col md:flex-row bg-white dark:bg-zinc-800 rounded-lg shadow-xl overflow-hidden">
            <!-- Columna del formulario -->
            <div class="w-full md:w-1/2 p-8 lg:p-12">
                <div class="max-w-md w-full mx-auto">
                    <div class="text-center md:text-left">
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">
                            {{ __('Crear una cuenta nueva') }}
                        </h2>
                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ __('Regístrate para acceder al sistema') }}
                        </p>
                    </div>

                    <!-- Mensajes de validación -->
                    <x-validation-errors class="mb-4" />

                    <!-- Formulario de registro -->
                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <div>
                            <x-label for="name" value="{{ __('Nombre completo') }}"
                                class="text-zinc-700 dark:text-zinc-300" />
                            <div class="mt-1">
                                <x-input id="name"
                                    class="block mt-1 w-full border-zinc-300 dark:border-zinc-700 dark:bg-zinc-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    type="text" name="name" :value="old('name')" required autofocus
                                    autocomplete="name" placeholder="Juan Pérez" />
                            </div>
                        </div>

                        <div>
                            <x-label for="email" value="{{ __('Correo electrónico') }}"
                                class="text-zinc-700 dark:text-zinc-300" />
                            <div class="mt-1">
                                <x-input id="email"
                                    class="block mt-1 w-full border-zinc-300 dark:border-zinc-700 dark:bg-zinc-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    type="email" name="email" :value="old('email')" required
                                    autocomplete="username" placeholder="juan.perez@ejemplo.com" />
                            </div>
                        </div>

                        <div>
                            <x-label for="password" value="{{ __('Contraseña') }}"
                                class="text-zinc-700 dark:text-zinc-300" />
                            <div class="mt-1">
                                <x-input id="password"
                                    class="block mt-1 w-full border-zinc-300 dark:border-zinc-700 dark:bg-zinc-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    type="password" name="password" required autocomplete="new-password"
                                    placeholder="Ingresa tu contraseña" />
                            </div>
                        </div>

                        <div>
                            <x-label for="password_confirmation" value="{{ __('Confirmar contraseña') }}"
                                class="text-zinc-700 dark:text-zinc-300" />
                            <div class="mt-1">
                                <x-input id="password_confirmation"
                                    class="block mt-1 w-full border-zinc-300 dark:border-zinc-700 dark:bg-zinc-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    type="password" name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Confirma tu contraseña" />
                            </div>
                        </div>

                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div>
                                <label for="terms" class="flex items-start">
                                    <x-checkbox id="terms" name="terms"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-zinc-300 dark:border-zinc-600 rounded mt-1" />
                                    <span class="ml-2 text-sm text-zinc-700 dark:text-zinc-300">
                                        {!! __('Acepto los :terms_of_service y :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">'.__('Términos de servicio').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">'.__('Política de privacidad').'</a>',
                                        ]) !!}
                                    </span>
                                </label>
                            </div>
                        @endif

                        <div>
                            <x-button type="submit"
                                class="w-full flex justify-center py-3 px-4 ">
                                {{ __('Registrarse') }}
                            </x-button>
                        </div>
                    </form>

                    <!-- Enlace de inicio de sesión -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ __('¿Ya tienes una cuenta?') }}
                            <a href="{{ route('login') }}"
                                class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                                {{ __('Inicia sesión') }}
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