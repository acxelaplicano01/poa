<x-action-section>
    <x-slot name="title">
        <span class="text-zinc-900 dark:text-zinc-100">{{ __('Eliminar Cuenta') }}</span>
    </x-slot>

    <x-slot name="description">
        <span class="text-zinc-600 dark:text-zinc-400">{{ __('Eliminar permanentemente tu cuenta.') }}</span>
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos se eliminarán permanentemente. Antes de eliminar tu cuenta, descarga cualquier información o datos que desees conservar.') }}
        </div>

        <div class="mt-5">
            <x-danger-button wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                {{ __('Eliminar Cuenta') }}
            </x-danger-button>
        </div>

        <!-- Modal de Confirmación para Eliminar Usuario -->
        <x-dialog-modal wire:model.live="confirmingUserDeletion">
            <x-slot name="title">
                <span class="dark:text-zinc-100">{{ __('Eliminar Cuenta') }}</span>
            </x-slot>

            <x-slot name="content">
                <div class="dark:text-zinc-300">{{ __('¿Estás seguro de que deseas eliminar tu cuenta? Una vez eliminada, todos sus recursos y datos se borrarán permanentemente. Por favor, ingresa tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.') }}</div>

                <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-input type="password" class="mt-1 block w-3/4 dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700"
                                autocomplete="current-password"
                                placeholder="{{ __('Contraseña') }}"
                                x-ref="password"
                                wire:model="password"
                                wire:keydown.enter="deleteUser" />

                    <x-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled" class="dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-700">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteUser" wire:loading.attr="disabled">
                    {{ __('Eliminar Cuenta') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>