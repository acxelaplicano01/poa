<x-app-layout>
    <header
        class="fixed top-0 right-0 left-0 z-30 sm:left-64 min-h-14 flex items-center bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700"
        data-flux-header="">

        <!-- Navbar para móviles -->
        <nav class="flex items-center gap-1 py-3 lg:hidden w-full px-4" data-flux-navbar="">
            <div class="flex items-center">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-zinc-500 rounded-lg sm:hidden hover:bg-zinc-100 focus:outline-none focus:ring-2 focus:ring-zinc-200 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-zinc-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="flex-1"></div>
        </nav>

        <!-- Contenedor principal para alineación de elementos -->
        <div class="flex items-center justify-between w-full px-6">
            <!-- Navbar con links de navegación 
            <nav class="flex items-center gap-1 py-3 overflow-x-auto overflow-y-hidden" data-flux-navbar="">
                <navbar-link href="#" data-current="data-current"
                    class="px-3 h-8 flex items-center rounded-lg relative px-2.5! text-zinc-500 dark:text-white/80 data-current:after:absolute data-current:after:-bottom-3 data-current:after:inset-x-0 data-current:after:h-[2px] hover:text-zinc-800 dark:hover:text-white data-current:text-(--color-accent-content) hover:bg-zinc-800/5 dark:hover:bg-white/10 data-current:after:bg-(--color-accent-content)"
                    data-flux-navbar-items="data-flux-navbar-items">
                    <div class="flex-1 text-sm font-medium leading-none whitespace-nowrap" data-content="">Mis
                        planificaciones</div>
                </navbar-link>
                <navbar-link href="#"
                    class="px-3 h-8 flex items-center rounded-lg relative px-2.5! text-zinc-500 dark:text-white/80 data-current:after:absolute data-current:after:-bottom-3 data-current:after:inset-x-0 data-current:after:h-[2px] hover:text-zinc-800 dark:hover:text-white data-current:text-(--color-accent-content) hover:bg-zinc-800/5 dark:hover:bg-white/10 data-current:after:bg-(--color-accent-content)"
                    data-flux-navbar-items="data-flux-navbar-items">
                    <div class="flex-1 text-sm font-medium leading-none whitespace-nowrap" data-content="">Requerir
                    </div>
                    <span
                        class="text-xs font-medium rounded-sm px-1 py-0.5 text-zinc-700 dark:text-zinc-200 bg-zinc-400/15 dark:bg-white/10 ms-2">32</span>
                <navbar-link>
                <navbar-link href="#"
                    class="px-3 h-8 flex items-center rounded-lg relative px-2.5! text-zinc-500 dark:text-white/80 data-current:after:absolute data-current:after:-bottom-3 data-current:after:inset-x-0 data-current:after:h-[2px] hover:text-zinc-800 dark:hover:text-white data-current:text-(--color-accent-content) hover:bg-zinc-800/5 dark:hover:bg-white/10 data-current:after:bg-(--color-accent-content)"
                    data-flux-navbar-items="data-flux-navbar-items">
                    <div class="flex-1 text-sm font-medium leading-none whitespace-nowrap" data-content="">Dar
                        seguimiento</div>
                <navbar-link>
                <navbar-link href="#"
                    class="px-3 h-8 flex items-center rounded-lg relative px-2.5! text-zinc-500 dark:text-white/80 data-current:after:absolute data-current:after:-bottom-3 data-current:after:inset-x-0 data-current:after:h-[2px] hover:text-zinc-800 dark:hover:text-white data-current:text-(--color-accent-content) hover:bg-zinc-800/5 dark:hover:bg-white/10 data-current:after:bg-(--color-accent-content)"
                    data-flux-navbar-items="data-flux-navbar-items">
                    <div class="flex-1 text-sm font-medium leading-none whitespace-nowrap" data-content="">Consolidado
                    </div>
                <navbar-link>
                <!-- Otros enlaces del navbar 
            </nav>-->

            <!-- Acciones del usuario alineadas a la derecha -->
            <div class="flex items-center ml-auto">
                <div class="flex items-center">
                    <div>
                        <button id="theme-toggle" type="button"
                            class="text-zinc-500 dark:text-white hover:bg-zinc-100 dark:hover:bg-zinc-700 focus:outline-none rounded-lg text-sm p-2">
                            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                    fill-rule="evenodd" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-section-border />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
