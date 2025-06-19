@props(['disabled' => false, 'error' => false])

<textarea {{ $disabled ? 'disabled' : '' }} 
    {!! $attributes->merge([
        'class' => 'border-zinc-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full' . 
        ($error ? ' border-red-500 dark:border-red-600 focus:border-red-500 dark:focus:border-red-600 focus:ring-red-500 dark:focus:ring-red-600' : '')
    ]) !!}
></textarea>