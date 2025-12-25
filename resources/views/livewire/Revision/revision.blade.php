<div>
	{{-- Care about people's approval and you will be their prisoner. --}}
	<div class="mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
		<div class="bg-white dark:bg-zinc-900 overflow-hidden shadow sm:rounded-lg p-4 sm:p-6">

			@if (session()->has('message'))
				<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
					<p class="font-medium">{{ session('message') }}</p>
				</div>
			@endif

			<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
				<h2 class="text-xl font-semibold text-zinc-800 dark:text-zinc-200">{{ __('Revisiones de Departamentos') }}</h2>

				<div class="flex flex-col sm:flex-row w-full sm:w-auto space-y-3 sm:space-y-0 sm:space-x-2">
					<div class="relative w-full sm:w-auto">
						<x-input wire:model.live="search" type="text" placeholder="Buscar departamento..." class="w-full pl-10 pr-4 py-2"/>
						<div class="absolute left-3 top-2.5">
							<svg class="h-5 w-5 text-zinc-500 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
							</svg>
						</div>
					</div>
					<div class="w-full sm:w-auto min-w-[150px] max-w-xs">
						<select wire:model.live="poaYear" class="block w-full min-w-[180px] max-w-xs rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm py-2 px-3">
							@foreach($poaYears as $year)
								<option value="{{ $year }}">POA {{ $year }}</option>
							@endforeach
						</select>
					</div>
					<div class="w-full sm:w-auto">
						<x-select 
							id="perPage" 
							wire:model.live="perPage"
							:options="[
								['value' => '10', 'text' => '10 por página'],
								['value' => '25', 'text' => '25 por página'],
								['value' => '50', 'text' => '50 por página'],
								['value' => '100', 'text' => '100 por página'],
							]"
							class="w-full"
						/>
					</div>
				</div>
			</div>

			<x-table
				sort-field="{{ $sortField }}"
				sort-direction="{{ $sortDirection }}"
				:columns="[
					['key' => 'departamento', 'label' => 'Departamento', 'sortable' => true],
					['key' => 'cantidad', 'label' => 'Cantidad de Actividades'],
					['key' => 'actions', 'label' => 'Acciones'],
				]"
				empty-message="{{ __('No se encontraron revisiones')}}"
				class="mt-6"
			>
				<x-slot name="desktop">
					@forelse ($revisiones as $rev)
						<tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
							<td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-300">
								<div class="flex items-center">
									<div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center mr-3">
										<svg class="h-4 w-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6" />
										</svg>
									</div>
									<div>
										<div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
											{{ $rev->name ?? '-' }}
										</div>
										@if(!empty($rev->siglas))
											<div class="text-xs text-zinc-500 dark:text-zinc-400">
												{{ $rev->siglas }}
											</div>
										@endif
									</div>
								</div>
							</td>
							<td class="px-6 py-4 whitespace-nowrap">
								<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300">
									{{ $rev->actividades_count ?? 0 }} {{ ($rev->actividades_count ?? 0) === 1 ? 'actividad' : 'actividades' }}
								</span>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
								<x-spinner-button x-spinner-button wire:click="verActividades({{ $rev->id }})" loadingTarget="verActividades({{ $rev->id }})" :loadingText="__('cargando...')" wire:click="verActividades({{ $rev->id }})"
									class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 active:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
									Ver Actividades
								</x-spinner-button>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="3" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
								{{ __('No se encontraron revisiones')}}
							</td>
						</tr>
					@endforelse
				</x-slot>

				<x-slot name="mobile">
					@forelse ($revisiones as $rev)
						<div class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 mb-4">
							<div class="flex justify-between items-start mb-2">
								<div>
									<span class="bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-300 px-2 py-1 rounded-full text-xs">
										{{ $rev->departamento->name ?? '-' }}
									</span>
								</div>
								<x-spinner-button x-spinner-button wire:click="verActividades({{ $rev->departamento->id }})" loadingTarget="verActividades({{ $rev->departamento->id }})" :loadingText="__('cargando...')" wire:click="verActividades({{ $rev->departamento->id }})"
									class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 active:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
									Ver Actividades
								</x-spinner-button>
							</div>
							<div class="text-zinc-600 dark:text-zinc-400 text-sm mb-1">
								<span class="font-semibold">Cantidad de Actividades:</span> {{ $rev->actividades_count ?? 0 }}
							</div>
						</div>
					@empty
						<div class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow text-center text-zinc-500 dark:text-zinc-400">
							{{__('No se encontraron revisiones')}}
						</div>
					@endforelse
				</x-slot>

				<x-slot name="footer">
					{{ $revisiones->links() }}
				</x-slot>
			</x-table>
		</div>
	</div>
</div>
