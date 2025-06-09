<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        <span class="text-zinc-900 dark:text-zinc-100">{{ __('Información del Perfil') }}</span>
    </x-slot>

    <x-slot name="description">
        <span class="text-zinc-600 dark:text-zinc-400">{{ __('Actualiza la información de tu perfil y dirección de correo electrónico.') }}</span>
    </x-slot>

    <x-slot name="form">
        <!-- Foto de Perfil -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Entrada de Archivo para Foto de Perfil -->
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Foto') }}" class="dark:text-zinc-300" />

                <!-- Foto de Perfil Actual -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full size-20 object-cover">
                </div>

                <!-- Vista Previa de Nueva Foto de Perfil -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 me-2 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-700" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Seleccionar Nueva Foto') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-700" wire:click="deleteProfilePhoto">
                        {{ __('Eliminar Foto') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Nombre -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Nombre') }}" class="dark:text-zinc-300" />
            <x-input id="name" type="text" class="mt-1 block w-full dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Correo Electrónico -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Correo Electrónico') }}" class="dark:text-zinc-300" />
            <x-input id="email" type="email" class="mt-1 block w-full dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2 dark:text-zinc-400">
                    {{ __('Tu dirección de correo electrónico no está verificada.') }}

                    <button type="button" class="underline text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-zinc-800" wire:click.prevent="sendEmailVerification">
                        {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.') }}
                    </p>
                @endif
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3 dark:text-green-400" on="saved">
            {{ __('Guardado.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-700">
            {{ __('Guardar') }}
        </x-button>
    </x-slot>
</x-form-section>