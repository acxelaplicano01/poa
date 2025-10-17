{{-- <x-rk.default::panels.full>
    {{ $slot ?? 'Contenido principal' }}
</x-rk.default::panels.full>   
 --}}

 {{-- <x-panel>
    {{ $slot ?? 'Contenido principal' }}
</x-panel>   --}}

 <x-rk.flux::app>
    {{ $slot ?? 'Contenido principal' }}
</x-rk.flux::app>    