<x-dialog-modal wire:model="isOpen" maxWidth="md">
    <x-slot name="title">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $isEditing ? 'Editar' : 'Nuevo' }} Usuario</h3>
            <button wire:click="closeModal" type="button" class="text-zinc-400 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-zinc-600 dark:hover:text-white">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Cerrar modal</span>
            </button>
        </div>
    </x-slot>

    <x-slot name="content">
        <form id="userForm" class="space-y-4">
            <div>
                <x-label for="name" value="{{ __('Nombre') }}" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" placeholder="Ingrese el nombre" />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-label for="email" value="{{ __('Correo Electrónico') }}" />
                <x-input id="email" type="email" class="mt-1 block w-full" wire:model="email" placeholder="Ingrese el correo electrónico" />
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-label for="password" value="{{ __('Contraseña') }}" />
                <x-input id="password" type="password" class="mt-1 block w-full" wire:model="password" placeholder="Ingrese la contraseña" />
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                    {{ $isEditing ? 'Dejar en blanco para mantener la contraseña actual.' : 'La contraseña debe tener al menos 8 caracteres.' }}
                </p>
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-label for="password_confirmation" value="{{ __('Confirmar Contraseña') }}" />
                <x-input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model="password_confirmation" placeholder="Confirme la contraseña" />
                @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <x-label for="roles" value="{{ __('Roles') }}" />
                <div class="mt-2 grid grid-cols-1 gap-3 max-h-60 overflow-y-auto p-2 border border-zinc-200 dark:border-zinc-700 rounded-md">
                    @forelse($roles ?? [] as $role)
                        @if(is_object($role))
                        <div class="py-1">
                            <x-toggle wire:model="selectedRoles" 
                                value="{{ $role->id }}">
                                {{ $role->name }}
                            </x-toggle>
                        </div>
                        @endif
                    @empty
                        <p class="text-zinc-500 dark:text-zinc-400">No hay roles disponibles</p>
                    @endforelse
                </div>
                @error('selectedRoles') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </form>
    </x-slot>

    <x-slot name="footer">
        <div class="flex justify-end gap-x-4">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-button wire:click="store" wire:loading.attr="disabled">
                {{ $isEditing ? __('Actualizar') : __('Guardar') }}
            </x-button>
        </div>
    </x-slot>
</x-dialog-modal>