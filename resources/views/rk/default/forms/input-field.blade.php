@props([
    'label' => '',
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'error' => null,
    'icon' => null,
    'helper' => null
])

<div class="space-y-2">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-400 w-5 h-5">{!! $icon !!}</span>
            </div>
        @endif
        
        <input 
            type="{{ $type }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge([
                'class' => 'block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200' . 
                          ($icon ? ' pl-10' : ' px-3') . 
                          ($error ? ' border-red-300 focus:border-red-500 focus:ring-red-500' : '') .
                          ' py-2.5'
            ]) }}
        >
    </div>
    
    @if($error)
        <p class="text-sm text-red-600 dark:text-red-400 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ $error }}
        </p>
    @elseif($helper)
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $helper }}</p>
    @endif
</div>
