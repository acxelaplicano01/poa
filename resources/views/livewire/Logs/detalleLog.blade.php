<x-app-layout>
    <div>
        <div class=" mx-auto rounded-lg mt-8 sm:mt-6 lg:mt-4 mb-6">
            <div class="overflow-hidden bg-white shadow sm:rounded-lg dark:bg-zinc-900">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        Log #{{ $log->id }}
                        <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $log->level_color }} text-white">
                            {{ ucfirst($log->level) }}
                        </span>
                    </h3>
                    <p class="max-w-2xl mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @if($log->created_at)
                            Registrado el {{ $log->created_at->format('d/m/Y') }} a las {{ $log->created_at->format('H:i:s') }}
                        @else
                            Fecha no disponible
                        @endif
                    </p>
                </div>
                <div class="border border-gray-200 dark:border-zinc-700">
                    <dl>
                        <div class="px-4 py-5 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-zinc-800">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                                Usuario
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                                @if($log->user_id)
                                    {{ $log->user_name }} (ID: {{ $log->user_id }})
                                @else
                                    {{ $log->user_name }}
                                @endif
                            </dd>
                        </div>
                        <div class="px-4 py-5 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-zinc-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                                Dirección IP
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                                {{ $log->ip_address ?? 'No disponible' }}
                            </dd>
                        </div>
                        <div class="px-4 py-5 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-zinc-800">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                                Módulo
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                                {{ $log->module }}
                            </dd>
                        </div>
                        <div class="px-4 py-5 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-zinc-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                                Acción
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        {!! $log->action_icon !!}
                                    </svg>
                                    {{ ucfirst($log->action) }}
                                </span>
                            </dd>
                        </div>
                        <div class="px-4 py-5 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-zinc-800">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                                Descripción
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                                {{ $log->description ?? 'Sin descripción' }}
                            </dd>
                        </div>
                        <div class="px-4 py-5 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-zinc-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                                Datos adicionales
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                                @if(!empty($log->data))
                                    <div class="p-4 overflow-auto bg-gray-100 rounded-md max-h-96 dark:bg-zinc-900">
                                        <pre class="text-xs text-gray-800 dark:text-gray-300">{{ json_encode($log->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">Sin datos adicionales</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>