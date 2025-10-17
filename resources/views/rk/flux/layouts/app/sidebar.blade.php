@php
    $headerItems = fp_navigation()
            ->loadContexts(['dashboard_navigators'])
            ->filterForCurrentUser()
            ->getSubBranch('header_group');
            
    $footerItems = fp_navigation()
            ->loadContexts(['dashboard_navigators'])
            ->filterForCurrentUser()
            ->getSubBranch('footer_group');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('rk.flux.partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-rk.flux::components.app-logo />
            </a>

            <flux:navlist variant="outline">
                               @foreach ($headerItems as $item)
                    @if ($item->isGroup())
                        <flux:navlist.group :heading="$item->label" class="grid">
                            @foreach ($item->items as $child)
                                <flux:navlist.item :href="$child->url" :icon="$child->heroIcon"
                                    :current="$child->isActive()" wire:navigate>
                                    {{ $child->label }}
                                </flux:navlist.item>
                            @endforeach
                        </flux:navlist.group>
                    @else
                        <flux:navlist.item :href="$item->url" :icon="$item->heroIcon" :current="$item->isActive()"
                            wire:navigate>
                            {{ $item->label }}
                        </flux:navlist.item>
                    @endif
                @endforeach

            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                @foreach ($footerItems as $item)
                    @if ($item->isGroup())
                        <flux:navlist.group :heading="$item->label" class="grid">
                            @foreach ($item->items as $child)
                                <flux:navlist.item :href="$child->url" :icon="$child->heroIcon"
                                    :current="$child->isActive()" wire:navigate>
                                    {{ $child->label }}
                                </flux:navlist.item>
                            @endforeach
                        </flux:navlist.group>
                    @else
                        <flux:navlist.item :href="$item->url" :icon="$item->heroIcon" :current="$item->isActive()"
                            wire:navigate>
                            {{ $item->label }}
                        </flux:navlist.item>
                    @endif
                @endforeach

            </flux:navlist>
            

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :email="auth()->user()->email"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->email }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->email }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}
        @fluxScripts
    </body>
</html>
