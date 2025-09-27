@props([
    'title' => '',
    'value' => '',
    'change' => null,
    'changeType' => 'positive',
    'icon' => null,
    'color' => 'blue'
])

@php
$colorClasses = [
    'blue' => 'from-blue-500 to-indigo-500',
    'green' => 'from-emerald-500 to-green-500',
    'purple' => 'from-purple-500 to-pink-500',
    'orange' => 'from-orange-500 to-red-500',
    'gray' => 'from-gray-500 to-slate-500'
];

$changeClasses = [
    'positive' => 'text-emerald-600 bg-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400',
    'negative' => 'text-red-600 bg-red-100 dark:bg-red-900/20 dark:text-red-400',
    'neutral' => 'text-gray-600 bg-zinc-100 dark:bg-zinc-800 dark:text-gray-400'
];
@endphp

<div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
            
            @if($change)
                <div class="flex items-center mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $changeClasses[$changeType] }}">
                        @if($changeType === 'positive')
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7V17"></path>
                            </svg>
                        @elseif($changeType === 'negative')
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17L7 7M7 7H17M7 7V17"></path>
                            </svg>
                        @endif
                        {{ $change }}
                    </span>
                </div>
            @endif
        </div>
        
        @if($icon)
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-r {{ $colorClasses[$color] }} rounded-lg flex items-center justify-center">
                    {!! $icon !!}
                </div>
            </div>
        @endif
    </div>
</div>
