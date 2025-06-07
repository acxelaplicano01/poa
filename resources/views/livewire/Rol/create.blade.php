<x-dialog-modal wire:model="isOpen">
    <x-slot name="title">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $isEditing ? 'Editar' : 'Nuevo' }} Rol</h3>
            <button wire:click="closeModal" type="button" class="text-zinc-400 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-zinc-600 dark:hover:text-white">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Cerrar modal</span>
            </button>
        </div>
    </x-slot>

    <x-slot name="content">
        <form id="roleForm">
            <div class="mb-4">
                <label for="name" class="block text-zinc-700 dark:text-zinc-300 font-semibold">Nombre del rol</label>
                <x-input wire:model="name" type="text" name="name" class="form-input mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white" id="name" />
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-zinc-700 dark:text-zinc-300 font-semibold">Descripción</label>
                <x-input wire:model="description" type="text" name="description" class="form-input mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white" id="description" />
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mt-4">
                <label class="block text-zinc-700 dark:text-zinc-300 font-semibold mb-2">Permisos</label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 max-h-60 overflow-y-auto p-2 border border-zinc-200 dark:border-zinc-700 rounded-md">
                    @forelse($permissions ?? [] as $permission)
                        <label class="flex items-center">
                            <x-checkbox wire:model="selectedPermissions" name="selectedPermissions[]" value="{{ $permission->id }}" class="form-checkbox" />
                            <span class="ml-2 dark:text-zinc-300 text-zinc-800">{{ $permission->name }}</span>
                        </label>
                    @empty
                        <p class="text-zinc-500 dark:text-zinc-400">No se encontraron permisos.</p>
                    @endforelse
                </div>
                @error('selectedPermissions')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </form>
    </x-slot>

    <x-slot name="footer">
        <div class="flex justify-end gap-x-4">
            <x-secondary-button wire:click="closeModal" type="button">
                Cancelar
            </x-secondary-button>
            <x-button wire:click="store" type="button">
                {{ $isEditing ? 'Actualizar' : 'Guardar' }}
            </x-button>
        </div>
    </x-slot>
</x-dialog-modal>