<!-- Modal de confirmación para eliminar cuenta mayor -->
<x-confirmation-modal wire:model="showDeleteModal">
    <x-slot name="title">
        {{ __('Eliminar Cuenta Mayor') }}
    </x-slot>

    <x-slot name="content">
        {{ __('¿Está seguro de que desea eliminar esta cuenta mayor?') }}
        
        @if($cuentaMayorToDelete)
            <div class="mt-4 p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-200">
                            {{ $cuentaMayorToDelete->nombre }}
                        </p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            ID: {{ $cuentaMayorToDelete->identificador }} | 
                            Grupo: {{ $cuentaMayorToDelete->grupoGasto->nombre ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <p class="mt-4 text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('Esta acción no se puede deshacer.') }}
        </p>
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="closeDeleteModal" wire:loading.attr="disabled">
            {{ __('Cancelar') }}
        </x-secondary-button>

        <x-danger-button class="ml-3" wire:click="delete" wire:loading.attr="disabled">
            {{ __('Eliminar') }}
        </x-danger-button>
    </x-slot>
</x-confirmation-modal>
