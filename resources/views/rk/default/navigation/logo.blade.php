<div class="flex items-center gap-3">
    <div class="flex aspect-square w-10 h-10 items-center justify-center rounded-2xl 
                bg-zinc-200 dark:bg-zinc-800 text-white">
        <img src="{{ asset('apple-touch-icon.png') }}" class="h-6 w-6">
    </div>
    <div>
        <h2 class="font-semibold text-gray-900 dark:text-white">{{ config('app.name', 'Laravel') }}</h2>
        <p class="text-xs text-gray-500 dark:text-gray-400">{{ config('app.description', 'Your app description') }}</p>
    </div>
</div>
