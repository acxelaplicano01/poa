<x-form-section submit="updatePassword">
    <x-slot name="title">
        <span class="text-zinc-900 dark:text-zinc-100">{{ __('Actualizar Contraseña') }}</span>
    </x-slot>

    <x-slot name="description">
        <span class="text-zinc-600 dark:text-zinc-400">{{ __('Asegúrate de que tu cuenta esté usando una contraseña larga y aleatoria para mantener la seguridad.') }}</span>
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-label for="current_password" value="{{ __('Contraseña Actual') }}" class="dark:text-zinc-300" />
            <x-input id="current_password" type="password" class="mt-1 block w-full dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700" wire:model="state.current_password" autocomplete="current-password" />
            <x-input-error for="current_password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="password" value="{{ __('Nueva Contraseña') }}" class="dark:text-zinc-300" />
            <x-input id="password" type="password" class="mt-1 block w-full dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700" wire:model="state.password" autocomplete="new-password" />
            <x-input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="password_confirmation" value="{{ __('Confirmar Contraseña') }}" class="dark:text-zinc-300" />
            <x-input id="password_confirmation" type="password" class="mt-1 block w-full dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700" wire:model="state.password_confirmation" autocomplete="new-password" />
            <x-input-error for="password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3 dark:text-green-400" on="saved">
            {{ __('Guardado.') }}
        </x-action-message>

        <x-button class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-700">
            {{ __('Guardar') }}
        </x-button>
    </x-slot>
</x-form-section>