@props([
    'wire:model' => 'showErrorModal',
    'title' => 'Error',
    'message' => '',
    'closeMethod' => 'closeErrorModal'
])

<!-- Modal de Error -->
<x-dialog-modal wire:model="{{ $attributes->get('wire:model', 'showErrorModal') }}" maxWidth="md">
    <x-slot name="title">
        <div class="flex items-center">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-red-600 dark:text-red-400">
                    {{ __($title) }}
                </h3>
            </div>
        </div>
    </x-slot>

    <x-slot name="content">
        <div class="mt-2">
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 dark:text-red-300">
                            {{ $message }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 dark:text-yellow-200">
                            {{ __('Si el problema persiste, contacte al administrador del sistema.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <div class="flex justify-end">
            <x-secondary-button wire:click="{{ $closeMethod }}">
                {{ __('Cerrar') }}
            </x-secondary-button>
        </div>
    </x-slot>
</x-dialog-modal>
