@php

    $headerQuery = rk_navigation()
        ->newQuery()
        ->loadContexts(['dashboard_navigators'])
        ->filterForCurrentUser();

    $headerItems = $headerQuery->get();

    $fullQuery = rk_navigation()
        ->newQuery()
        ->loadContexts(['dashboard_navigators', 'rk_footer'])
        ->filterForCurrentUser();

    $parentActiveNode = $fullQuery->getActiveNodeParentWithChildren();
    $breadcrumbs = $fullQuery->getBreadcrumbsForCurrentRoute();
    $activeNode = $fullQuery->getCurrentActiveNode();

    $footerItems = rk_navigation()
        ->newQuery()
        ->loadContexts(['rk_footer'])
        ->filterForCurrentUser()
        ->get();

@endphp


<x-rk.default::panels.base>
    <x-slot name="sidebar">

        <x-rk.default::navigation.sidebar>

            <x-slot name="header">
                <x-rk.default::navigation.logo />
            </x-slot>

            <x-ui.separator class="my-4" separator="sm" :border="false" />

            @foreach ($headerItems as $item)
                @if ($item->isGroup() && $item->items->isNotEmpty())
                    <x-rk.default::navigation.group-items :label="$item->getLabel()" :icon="$item->getIcon()" :active="$item->isActive() || ($parentActiveNode && $activeNode->id === $item->id)">
                        @foreach ($item->items as $child)
                            <x-rk.default::navigation.item href="{{ $child->getUrl() }}"
                                badge="{{ $child->getFinalBage() }}" icon="heroicon-o-{{ $child->getHeroIcon() }}"
                                title="{{ $child->getLabel() }}" :active="$child->isActive()" />
                        @endforeach
                    </x-rk.default::navigation.group-items>
                @else
                    <x-rk.default::navigation.item href="{{ $item->getUrl() }}" badge="{{ $item->getFinalBage() }}"
                        icon="heroicon-o-{{ $item->getHeroIcon() }}" title="{{ $item->getLabel() }}"
                        :active="$item->isActive()" />
                @endif
            @endforeach




            <x-slot name="footer">
                <x-rk.default::navigation.footer>
                    @foreach ($footerItems as $item)
                        @if ($item->isGroup() && $item->items->isNotEmpty())
                            <x-rk.default::navigation.group-items :label="$item->getLabel()" :icon="$item->getIcon()" :active="$item->isActive() || ($parentActiveNode && $activeNode->id === $item->id)">
                                @foreach ($item->items as $child)
                                    <x-rk.default::navigation.item href="{{ $child->getUrl() }}"
                                        badge="{{ $child->getFinalBage() }}"
                                        icon="heroicon-o-{{ $child->getHeroIcon() }}" title="{{ $child->getLabel() }}"
                                        :active="$child->isActive()" />
                                @endforeach
                            </x-rk.default::navigation.group-items>
                        @else
                            <x-rk.default::navigation.item href="{{ $item->getUrl() }}"
                                badge="{{ $item->getFinalBage() }}" icon="heroicon-o-{{ $item->getHeroIcon() }}"
                                title="{{ $item->getLabel() }}" :active="$item->isActive()" />
                        @endif
                    @endforeach

                </x-rk.default::navigation.footer>
            </x-slot>

        </x-rk.default::navigation.sidebar>

    </x-slot>

    <x-slot name="header">
        <x-rk.default::navigation.sidebar-header>

            <x-slot name="rightMenu">
                <x-rk.default::navigation.search />
                <x-rk.default::ui.theme-toggle />
                <x-rk.default::dropdowns.profile-dropdown :userName="auth()->user()->name ?? 'Acxel'" :userEmail="auth()->user()->email ?? 'acxel@example.com'"
                    class="bg-zinc-100 dark:bg-zinc-800">

                    <x-rk.default::navigation.item href="{{ route('profile.show') }}"
                        icon="heroicon-o-user-circle" class="w-full" title="{{ __('Profile') }}" />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <x-rk.default::navigation.item as="button" type="submit"
                            icon="heroicon-o-arrow-right-start-on-rectangle" class="w-full cursor-pointer"
                            title="{{ __('Cerrar Sesión') }}" />
                    </form>
                </x-rk.default::dropdowns.profile-dropdown>
            </x-slot>


        </x-rk.default::navigation.sidebar-header>

    </x-slot>


    <div class="p-4 w-full md:max-w-7xl mx-auto transition-all duration-300 ease-in-out">


        {{-- Breadcrumbs --}}
        {{-- <x-rk.default::breadcrumbs.simple :items="$breadcrumbs" /> --}}
        @if (count($breadcrumbs) > 0)
            <x-rk.default::breadcrumbs.menu>
                @foreach ($breadcrumbs as $breadcrumb)
                    @if (!$loop->last)
                        <x-rk.default::breadcrumbs.item
                            href="{{ $breadcrumb->getUrl() }}">{{ $breadcrumb->getLabel() }}</x-rk.default::breadcrumbs.item>
                    @else
                        <x-rk.default::breadcrumbs.item
                            active>{{ $breadcrumb->getLabel() }}</x-rk.default::breadcrumbs.item>
                    @endif
                @endforeach
            </x-rk.default::breadcrumbs.menu>
        @endif


        @if (!is_null($parentActiveNode))
            <x-rk.default::tabs.tab-menu>
                @foreach ($parentActiveNode?->items as $item)
                    <x-rk.default::tabs.tab-item tab="{{ $item->getLabel() }}" :href="$item->getUrl()" :active="$item->isActive()"
                        :badge="$item->getFinalBage()" badge-color="pink" icon="heroicon-o-{{ $item->getHeroIcon() }}" />
                @endforeach
            </x-rk.default::tabs.tab-menu>
        @endif



        <x-rk.default::text.title title="{{ $activeNode ? $activeNode->getLabel() : 'Dashboard' }}"
            subtitle="{{ $activeNode ? $activeNode->getDescription() : 'Welcome to your dashboard' }}" />

        <x-rk.default::ui.separator class="my-4" />
        {{ $slot }}

    </div>

</x-rk.default::panels.base>
