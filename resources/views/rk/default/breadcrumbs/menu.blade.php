@props([
    'class' => '',
])

<nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => "py-4 flex items-center space-x-1 text-sm text-zinc-500 dark:text-zinc-400 $class"]) }}>
    <ol class="inline-flex items-center space-x-1">
        {{ $slot }}
    </ol>
</nav>
