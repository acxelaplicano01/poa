@props([
    'headers' => [],
    'rows' => [],
    'sortable' => false,
    'searchable' => false,
    'pagination' => false
])

<div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden"
     x-data="{
         search: '',
         sortColumn: '',
         sortDirection: 'asc',
         currentPage: 1,
         itemsPerPage: 10,
         
         get filteredRows() {
             let filtered = this.rows;
             if (this.search) {
                 filtered = filtered.filter(row => 
                     Object.values(row).some(value => 
                         String(value).toLowerCase().includes(this.search.toLowerCase())
                     )
                 );
             }
             return filtered;
         },
         
         get sortedRows() {
             if (!this.sortColumn) return this.filteredRows;
             
             return [...this.filteredRows].sort((a, b) => {
                 let aVal = a[this.sortColumn];
                 let bVal = b[this.sortColumn];
                 
                 if (this.sortDirection === 'asc') {
                     return aVal > bVal ? 1 : -1;
                 } else {
                     return aVal < bVal ? 1 : -1;
                 }
             });
         },
         
         get paginatedRows() {
             if (!this.pagination) return this.sortedRows;
             
             const start = (this.currentPage - 1) * this.itemsPerPage;
             return this.sortedRows.slice(start, start + this.itemsPerPage);
         },
         
         sort(column) {
             if (this.sortColumn === column) {
                 this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
             } else {
                 this.sortColumn = column;
                 this.sortDirection = 'asc';
             }
         },
         
         rows: @js($rows)
     }">
    
    @if($searchable)
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"></path>
                </svg>
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="Buscar..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-zinc-50 dark:bg-zinc-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
        </div>
    @endif
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-zinc-50 dark:bg-zinc-700">
                <tr>
                    @foreach($headers as $key => $header)
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            @if($sortable)
                                <button @click="sort('{{ $key }}')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                    <span>{{ $header }}</span>
                                    <svg class="w-4 h-4" :class="sortColumn === '{{ $key }}' ? 'text-blue-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </button>
                            @else
                                {{ $header }}
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
                <template x-for="(row, index) in paginatedRows" :key="index">
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors duration-150">
                        @foreach($headers as $key => $header)
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100" x-text="row.{{ $key }}"></td>
                        @endforeach
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
    
    @if($pagination)
        <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-700 border-t border-gray-200 dark:border-gray-600 flex items-center justify-between">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                Mostrando <span x-text="((currentPage - 1) * itemsPerPage) + 1"></span> a 
                <span x-text="Math.min(currentPage * itemsPerPage, sortedRows.length)"></span> de 
                <span x-text="sortedRows.length"></span> resultados
            </div>
            <div class="flex space-x-2">
                <button @click="currentPage = Math.max(1, currentPage - 1)" 
                        :disabled="currentPage === 1"
                        class="px-3 py-1 text-sm bg-white dark:bg-zinc-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-zinc-50 dark:hover:bg-zinc-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    Anterior
                </button>
                <button @click="currentPage = Math.min(Math.ceil(sortedRows.length / itemsPerPage), currentPage + 1)"
                        :disabled="currentPage >= Math.ceil(sortedRows.length / itemsPerPage)"
                        class="px-3 py-1 text-sm bg-white dark:bg-zinc-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-zinc-50 dark:hover:bg-zinc-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    Siguiente
                </button>
            </div>
        </div>
    @endif
</div>
