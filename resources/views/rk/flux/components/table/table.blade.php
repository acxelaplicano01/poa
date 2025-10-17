@props(['paginate' => null])
<div class="lg:p-2">
    <div class="overflow-x-auto rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            {{ $slot }}
        </table>
    </div>
    @isset($paginate)
        <x-rk.flux::components.table.paginate :paginator="$paginate" />
    @endisset

</div>
