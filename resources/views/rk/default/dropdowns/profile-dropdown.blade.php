@props([
    'user' => null,
    'position' => null,
    'avatarBgColor' => 'bg-[var(--color-accent)] text-[var(--color-accent-foreground)]  ',
    'avatarSize' => 'md',
    'avatarText' => 'U',
    'options' => '',
    'userName' => null,
    'userEmail' => null,
    'responsiveText' => true,
])
<x-rk.default::dropdowns.menu-dropdown :position="$position" 
    :width="'w-64'" 
    :label="''" 
    :responsiveLabel="false" 
    class="border border-gray-300 dark:border-gray-600 rounded-2xl"
    triggerClass="  rounded-2xl"
>
    <!-- Trigger personalizado -->
    <x-slot name="trigger">
        <div class="flex items-center space-x-3 min-w-0 max-w-xs rounded-2xl" style="cursor: pointer;">
            <!-- Avatar -->
            <x-rk.default::ui.avatar :user="$user" :bgColor="$avatarBgColor" :size="$avatarSize" :text="$avatarText" />

            <!-- Nombre y correo -->
            @if ($userName || $userEmail)
                <div class="flex flex-col text-left min-w-0 max-w-[calc(100%-3rem)]">
                    @if ($userName)
                        <span
                            class="font-medium text-gray-700 dark:text-gray-300 truncate overflow-hidden whitespace-nowrap @if ($responsiveText) hidden sm:inline @endif"
                            title="{{ $userName }}">
                            {{ $userName }}
                        </span>
                    @endif
                    @if ($userEmail)
                        <span
                            class="text-sm text-gray-500 dark:text-gray-400 truncate overflow-hidden whitespace-nowrap @if ($responsiveText) hidden sm:inline @endif"
                            title="{{ $userEmail }}">
                            {{ $userEmail }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </x-slot>

    <!-- Contenido del dropdown -->
    <div class="px-2 py-2 max-w-sm">
        <div class="flex items-center space-x-3">
            <x-rk.default::ui.avatar :user="$user" :bgColor="$avatarBgColor" :size="$avatarSize" :text="$avatarText" />
            <div class="min-w-0 max-w-[calc(100%-3rem)]">
                <div class="font-medium text-gray-900 dark:text-white truncate overflow-hidden whitespace-nowrap"
                    title="{{ $userName ?? ($user['name'] ?? 'Usuario') }}">
                    {{ $userName ?? ($user['name'] ?? 'Usuario') }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400 truncate overflow-hidden whitespace-nowrap"
                    title="{{ $userEmail ?? ($user['email'] ?? 'usuario@ejemplo.com') }}">
                    {{ $userEmail ?? ($user['email'] ?? 'usuario@ejemplo.com') }}
                </div>
            </div>
        </div>

        <x-rk.default::ui.separator class="my-1" />

        <div class="py-1">
            {{ $slot }}
        </div>
    </div>
</x-rk.default::dropdowns.menu-dropdown>
