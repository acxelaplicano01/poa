<!-- filepath: c:\Users\acxel\Desktop\Desarrollo\Git Repos\POA\resources\views\components\year-picker.blade.php -->
@props(['id' => null, 'name' => null, 'value' => null, 'minYear' => 2000, 'maxYear' => 2050])

@php
$id = $id ?? $name ?? 'year-picker-'.uniqid();
$currentYear = date('Y');
@endphp

<div
    x-data="{
        selectedYear: @entangle($attributes->wire('model')).live,
        showPicker: false,
        currentYear: {{ $currentYear }},
        minYear: {{ $minYear }},
        maxYear: {{ $maxYear }},
        currentDecade: {{ floor($currentYear / 10) * 10 }},
        
        init() {
            // Sincronizar valor inicial si existe
            if ('{{ $value }}') {
                this.selectedYear = '{{ $value }}';
            }
            
            // Cierra el picker al hacer clic fuera
            document.addEventListener('click', (e) => {
                if (this.showPicker && !this.$el.contains(e.target)) {
                    this.showPicker = false;
                }
            });
        },
        
        previousDecade() {
            if (this.currentDecade - 10 >= Math.floor(this.minYear / 10) * 10) {
                this.currentDecade -= 10;
            }
        },
        
        nextDecade() {
            if (this.currentDecade + 10 <= Math.floor(this.maxYear / 10) * 10) {
                this.currentDecade += 10;
            }
        },
        
        selectYear(year) {
            this.selectedYear = year;
            this.showPicker = false;
            
            // Disparar evento para notificar cambios
            this.$dispatch('year-selected', { year: year });
        },
        
        // Años para la década actual
        getYearsForCurrentDecade() {
            const years = [];
            for (let y = this.currentDecade; y < this.currentDecade + 10; y++) {
                if (y >= this.minYear && y <= this.maxYear) {
                    years.push(y);
                }
            }
            return years;
        }
    }"
    class="relative inline-block w-full"
>
    <!-- Input hidden para el formulario -->
    <input type="hidden" name="{{ $name }}" x-model="selectedYear" />
    
    <!-- Input visible -->
    <div class="relative">
        <input
            x-model="selectedYear"
            type="text"
            id="{{ $id }}"
            {{ $attributes->merge(['class' => 'w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-zinc-600 focus:ring-indigo-500 dark:focus:ring-zinc-600 shadow-sm py-2 pl-3 pr-10']) }}
            placeholder="Seleccionar año"
            pattern="[0-9]{4}"
            maxlength="4"
            @click="showPicker = !showPicker"
            readonly
        />
        <div class="absolute inset-y-0 right-0 flex items-center px-2">
            <button 
                type="button" 
                class="h-full px-1 text-zinc-400 focus:outline-none"
                @click="showPicker = !showPicker"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Year picker dropdown -->
    <div
        x-show="showPicker"
        x-cloak
        style="position: absolute; top: -230px; left: 0; z-index: 9999;"
        class="w-64 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-lg shadow-lg overflow-hidden"
    >
        <!-- Header con navegación por décadas -->
        <div class="bg-zinc-50 dark:bg-zinc-700 px-4 py-2 flex justify-between items-center">
            <button 
                type="button"
                @click="previousDecade()" 
                @click.stop
                class="text-zinc-600 dark:text-zinc-300 hover:text-zinc-800 dark:hover:text-zinc-100"
                :disabled="currentDecade <= minYear"
                :class="{ 'opacity-50 cursor-not-allowed': currentDecade <= minYear }"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
            <span x-text="currentDecade + ' - ' + (currentDecade + 9)" class="font-semibold text-zinc-800 dark:text-zinc-200"></span>
            <button 
                type="button"
                @click="nextDecade()"
                @click.stop
                class="text-zinc-600 dark:text-zinc-300 hover:text-zinc-800 dark:hover:text-zinc-100"
                :disabled="currentDecade + 10 > maxYear"
                :class="{ 'opacity-50 cursor-not-allowed': currentDecade + 10 > maxYear }"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        
        <!-- Grid de años -->
        <div class="p-2 grid grid-cols-4 gap-2">
            <template x-for="year in getYearsForCurrentDecade()" :key="year">
                <button
                    type="button"
                    @click="selectYear(year)"
                    @click.stop
                    class="px-2 py-1 rounded text-sm font-medium transition-colors"
                    :class="year == selectedYear 
                        ? 'bg-indigo-600 text-white' 
                        : 'hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200'"
                    x-text="year"
                ></button>
            </template>
        </div>
        
        <!-- Footer con acciones -->
        <div class="bg-zinc-50 dark:bg-zinc-700 px-4 py-2 flex justify-between">
            <button 
                type="button"
                @click="selectYear(currentYear)"
                @click.stop 
                class="text-sm text-indigo-600 dark:text-zinc-400 hover:text-indigo-800 dark:hover:text-zinc-300 font-medium"
            >
                Hoy
            </button>
            <button 
                type="button"
                @click="showPicker = false"
                @click.stop 
                class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 font-medium"
            >
                Cancelar
            </button>
        </div>
    </div>
</div>