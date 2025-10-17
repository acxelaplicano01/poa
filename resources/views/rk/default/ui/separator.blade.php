@props([
    'separator' => '',
    'border' => true,
])

@php
$separatorClasses = [
    'sm' => ' mb-2',
    'md' => ' mb-4',
    'lg' => ' mb-4',
    'xl' => 'mb-6',
];
$separatorClass = $separatorClasses[$separator] ?? 'my-2';
@endphp


<div class=" @if($border) border-t border-zinc-200 dark:border-zinc-700 @endif 
 {{ $separatorClass }}">

</div>
