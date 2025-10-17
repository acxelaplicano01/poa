@php

    $query = rk_navigation()
        ->newQuery()
        ->loadContexts(['dashboard_navigators'])
        ->filterForCurrentUser();

    $activo = $query->get();
    $breadcrumbs = $query->getBreadcrumbsForCurrentRoute();
    $activeNode = $query->getCurrentActiveNode();

@endphp

<x-rk.flux::layouts.app.header :title="$title ?? null">
    <flux:main>
        @if ($breadcrumbs->isNotEmpty() && $breadcrumbs->count() > 1)
            <flux:breadcrumbs class="mb-4">

                @foreach ($breadcrumbs as $breadcrumb)
                    <flux:breadcrumbs.item href="{{ $breadcrumb->getUrl() }}" separator="slash">
                        {{ $breadcrumb->getLabel() }}
                    </flux:breadcrumbs.item>
                @endforeach

            </flux:breadcrumbs>
        @endif

        <flux:heading size="xl">
            {{ $activeNode?->getLabel() ?? '' }}
        </flux:heading>
        <flux:subheading>
            {{ $activeNode?->getDescription() ?? '' }}
        </flux:subheading>
        <flux:separator variant="subtle" class="mt-2 mb-4" />
        {{ $slot }}
    </flux:main>
</x-rk.flux::layouts.app.header>
