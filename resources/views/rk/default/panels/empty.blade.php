<x-rk.default::panels.base>

    <x-slot name="sidebar">
        <x-rk.default::navigation.sidebar>
            <x-slot name="header">
                <x-rk.default::navigation.logo />
            </x-slot>

            <x-rk.default::navigation.menu-items title="Main Menu" :active="true" >
                <x-rk.default::navigation.item href="/usuarios" icon="heroicon-o-home-modern" title="Usuarios" visible="true" badge="2"
                    active="true" badgePosition="left" />
            </x-rk.default::navigation.menu-items>



            <x-slot name="footer">
                <x-rk.default::navigation.footer>
                    <x-rk.default::navigation.item href="#" icon="heroicon-o-cog-6-tooth" title="Settings" />
                </x-rk.default::navigation.footer>
            </x-slot>
        </x-rk.default::navigation.sidebar>
    </x-slot>

    <div class="p-4 w-full md:max-w-7xl mx-auto transition-all duration-300 ease-in-out">
        <x-rk.default::text.title class="mb-4">{{ $title ?? 'Panel Title' }}</x-rk.default::text.title>
        <x-rk.default::text.description class="mb-4">{{ $description ?? 'Panel Description' }}</x-rk.default::text.description>
        {{ $slot }}
    </div>

</x-rk.default::panels.base>
