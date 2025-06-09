@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
        <div class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
            {{ $title }}
        </div>
    </div>

    <div class="px-6 py-4">
        {{ $content }}
    </div>

    <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800 text-right border-t border-zinc-200 dark:border-zinc-700">
        {{ $footer }}
    </div>
</x-modal>