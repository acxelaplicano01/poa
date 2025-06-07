@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 dark:bg-zinc-900 dark:text-zinc-200 dark:border-zinc-700 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-zinc-500 dark:focus:ring-zinc-500  rounded-md shadow-sm']) !!}>
