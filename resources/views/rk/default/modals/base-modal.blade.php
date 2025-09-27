@props([
    'show' => false, // Modal cerrado por defecto
    'closeOnEsc' => true,
    'closeOnClickOutside' => true,
    'closeButton' => true,
    'closePosition' => 'right' // 'right' o 'left'
])

<div x-data="{ show: @js($show) }">
    <!-- Trigger opcional -->
    @isset($trigger)
        <div @click="show = true">
            {{ $trigger }}
        </div>
    @endisset

    <!-- Modal -->
    <div x-show="show"
         x-on:keydown.escape.window="@js($closeOnEsc) ? show = false : ''"
         x-on:close-modal.window="show = false"
         x-on:open-modal.window="show = true"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">

        <!-- Backdrop suave -->
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/20 backdrop-blur-sm">
        </div>

        <!-- Contenido del Modal -->
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full flex flex-col items-center"
                 @click.away="@js($closeOnClickOutside) ? show = false : ''">

               

                <!-- Slot principal -->
                {{ $slot }}
            </div>
        </div>
    </div>
</div>