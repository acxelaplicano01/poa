
<div class="border-t border-zinc-200 dark:border-zinc-700 p-3">
    <div class="space-y-2">
        {{ $slot ?? '' }}
        <x-rk.default::dropdowns.profile-dropdown :userName="auth()->user()->name ?? 'Fran'" :userEmail="auth()->user()->email ?? 'fran@example.com'"
            class="bg-zinc-100 dark:bg-zinc-800">
           
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <x-rk.default::navigation.item as="button" type="submit"
                    icon="heroicon-o-arrow-right-start-on-rectangle" class="w-full" title="{{ __('Log Out') }}" />
            </form>
        </x-rk.default::dropdowns.profile-dropdown>
    </div>
</div>
