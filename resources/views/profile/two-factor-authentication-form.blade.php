<x-action-section>
    <x-slot name="title">
        <span class="text-zinc-900 dark:text-zinc-100">{{ __('Autenticación de Dos Factores') }}</span>
    </x-slot>

    <x-slot name="description">
        <span class="text-zinc-600 dark:text-zinc-400">{{ __('Añade seguridad adicional a tu cuenta utilizando la autenticación de dos factores.') }}</span>
    </x-slot>

    <x-slot name="content">
        <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
            @if ($this->enabled)
                @if ($showingConfirmation)
                    {{ __('Finaliza la activación de la autenticación de dos factores.') }}
                @else
                    {{ __('Has activado la autenticación de dos factores.') }}
                @endif
            @else
                {{ __('No has activado la autenticación de dos factores.') }}
            @endif
        </h3>

        <div class="mt-3 max-w-xl text-sm text-zinc-600 dark:text-zinc-400">
            <p>
                {{ __('Cuando la autenticación de dos factores está activada, se te solicitará un token seguro y aleatorio durante la autenticación. Puedes obtener este token desde la aplicación Google Authenticator de tu teléfono.') }}
            </p>
        </div>

        @if ($this->enabled)
            @if ($showingQrCode)
                <div class="mt-4 max-w-xl text-sm text-zinc-600 dark:text-zinc-400">
                    <p class="font-semibold">
                        @if ($showingConfirmation)
                            {{ __('Para finalizar la activación de la autenticación de dos factores, escanea el siguiente código QR usando la aplicación de autenticación de tu teléfono o ingresa la clave de configuración y proporciona el código OTP generado.') }}
                        @else
                            {{ __('La autenticación de dos factores está ahora activada. Escanea el siguiente código QR usando la aplicación de autenticación de tu teléfono o ingresa la clave de configuración.') }}
                        @endif
                    </p>
                </div>

                <div class="mt-4 p-2 inline-block bg-white">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>

                <div class="mt-4 max-w-xl text-sm text-zinc-600 dark:text-zinc-400">
                    <p class="font-semibold">
                        {{ __('Clave de Configuración') }}: <span class="dark:text-white">{{ decrypt($this->user->two_factor_secret) }}</span>
                    </p>
                </div>

                @if ($showingConfirmation)
                    <div class="mt-4">
                        <x-label for="code" value="{{ __('Código') }}" class="dark:text-zinc-300" />

                        <x-input id="code" type="text" name="code" class="block mt-1 w-1/2 dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700" inputmode="numeric" autofocus autocomplete="one-time-code"
                            wire:model="code"
                            wire:keydown.enter="confirmTwoFactorAuthentication" />

                        <x-input-error for="code" class="mt-2" />
                    </div>
                @endif
            @endif

            @if ($showingRecoveryCodes)
                <div class="mt-4 max-w-xl text-sm text-zinc-600 dark:text-zinc-400">
                    <p class="font-semibold">
                        {{ __('Guarda estos códigos de recuperación en un gestor de contraseñas seguro. Pueden utilizarse para recuperar el acceso a tu cuenta si pierdes tu dispositivo de autenticación de dos factores.') }}
                    </p>
                </div>

                <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-zinc-100 dark:bg-zinc-800 dark:text-zinc-200 rounded-lg">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div>{{ $code }}</div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="mt-5">
            @if (! $this->enabled)
                <x-confirms-password wire:then="enableTwoFactorAuthentication">
                    <x-button type="button" wire:loading.attr="disabled" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-700">
                        {{ __('Activar') }}
                    </x-button>
                </x-confirms-password>
            @else
                @if ($showingRecoveryCodes)
                    <x-confirms-password wire:then="regenerateRecoveryCodes">
                        <x-secondary-button class="me-3 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-700">
                            {{ __('Regenerar Códigos de Recuperación') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @elseif ($showingConfirmation)
                    <x-confirms-password wire:then="confirmTwoFactorAuthentication">
                        <x-button type="button" class="me-3 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-700" wire:loading.attr="disabled">
                            {{ __('Confirmar') }}
                        </x-button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="showRecoveryCodes">
                        <x-secondary-button class="me-3 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-700">
                            {{ __('Mostrar Códigos de Recuperación') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @endif

                @if ($showingConfirmation)
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <x-secondary-button wire:loading.attr="disabled" class="dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-700">
                            {{ __('Cancelar') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <x-danger-button wire:loading.attr="disabled">
                            {{ __('Desactivar') }}
                        </x-danger-button>
                    </x-confirms-password>
                @endif

            @endif
        </div>
    </x-slot>
</x-action-section>