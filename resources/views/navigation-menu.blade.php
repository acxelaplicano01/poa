<div>
   @php
   function isActiveModule($module)
   {
      $currentRoute = request()->route() ? request()->route()->getName() : '';
      $moduleConfig = config('rutas.' . $module, []);
      
      // Si el módulo tiene una ruta directa y coincide con la actual
      if (isset($moduleConfig['route']) && $currentRoute == $moduleConfig['route']) {
         return true;
      }
      
      // Verificar todas las rutas en todos los items del módulo
      if (isset($moduleConfig['items']) && is_array($moduleConfig['items'])) {
         foreach ($moduleConfig['items'] as $item) {
            if (isset($item['routes']) && is_array($item['routes'])) {
               if (in_array($currentRoute, $item['routes'])) {
                  return true;
               }
            }
         }
      }
      
      return false;
   }

   function hasModuleAccess($module)
   {
      $user = auth()->user();
      if (!$user) return false;

      // Super admin tiene acceso a todo
      if ($user->hasRole('super-admin')) return true;
      
      // Verificar la configuración del módulo
      $moduleConfig = config('rutas.' . $module, []);
      
      // Si hay algún item con always_visible
      if (isset($moduleConfig['items']) && is_array($moduleConfig['items'])) {
         foreach ($moduleConfig['items'] as $item) {
            if (isset($item['always_visible']) && $item['always_visible']) {
               return true;
            }
            
            // Verificar permisos de items
            if (isset($item['permisos']) && is_array($item['permisos'])) {
               foreach ($item['permisos'] as $permiso) {
                  if ($user->can($permiso)) {
                     return true;
                  }
               }
            }
         }
      }
      
      // Verificar permiso general del módulo
      $permiso = "acceso-{$module}";
      return $user->can($permiso);
   }

   // Obtener la configuración del menú
   $moduleConfig = config('rutas', []);
   @endphp
   
   <aside id="logo-sidebar"
      class="fixed top-0 left-0 z-40 w-64 h-screen pt-4 transition-transform -translate-x-full bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700 border-r sm:translate-x-0"
      aria-label="Sidebar">
      <div class="h-full px-3 pb-4 overflow-y-auto barra dark:barra bg-zinc-50 dark:bg-zinc-900 flex flex-col">
         <ul class="space-y-2 font-medium flex-grow">
            <div class="flex items-center justify-start rtl:justify-end mb-6">
               <a href="/dashboard" class="flex ms-2 md:me-24">
                  <!-- Logo para modo claro -->
                  <img src="{{ asset('Logo/poav2_grey.png') }}" alt="Logo" height="80px" width="80px"
                     class="dark:hidden" />

                  <!-- Logo para modo oscuro -->
                  <img src="{{ asset('Logo/poav2.webp') }}" alt="Logo" height="80px" width="80px"
                     class="hidden dark:block" />

                  <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white"></span>
               </a>
            </div>

            {{-- Generar menú principal dinámicamente - SOLO MÓDULOS --}}
            @foreach($moduleConfig as $moduleKey => $moduleData)
               @if(!isset($moduleData['footer']) || !$moduleData['footer'])
                  @if(hasModuleAccess($moduleKey))
                     <li>
                        <x-sidebar-link href="{{ route($moduleData['route']) }}" :active="isActiveModule($moduleKey)">
                           <x-activeIcons :active="isActiveModule($moduleKey)" class="w-5 h-5 ml-2" aria-hidden="true"
                              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                              {!! $moduleData['icono'] !!}
                           </x-activeIcons>
                           <span class="ms-3">{{ $moduleData['titulo'] }}</span>
                        </x-sidebar-link>
                     </li>
                  @endif
               @endif
            @endforeach
         </ul>

         <!-- Componente de perfil al final del sidebar -->
         <div class="mt-auto border-zinc-200 dark:border-zinc-700 pt-4">
            <ul class="space-y-2 font-medium flex-grow mb-6">
               {{-- Generar menú de footer dinámicamente --}}
               @foreach($moduleConfig as $moduleKey => $moduleData)
                  @if(isset($moduleData['footer']) && $moduleData['footer'] && hasModuleAccess($moduleKey))
                     <li>
                        <x-sidebar-link href="{{ route($moduleData['route']) }}" :active="isActiveModule($moduleKey)">
                           <x-activeIcons :active="isActiveModule($moduleKey)" class="w-5 h-5 ml-2" aria-hidden="true"
                              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                              {!! $moduleData['icono'] !!}
                           </x-activeIcons>
                           <span class="ms-3">{{ $moduleData['titulo'] }}</span>
                        </x-sidebar-link>
                     </li>
                  @endif
               @endforeach
               
               <li>
                  <x-sidebar-link href="https://chat.whatsapp.com/CnEA4qNlOBoLK1Hh8NKsKI">
                     <svg class="w-5 h-5 ml-2 text-zinc-500 transition duration-75 dark:text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                     </svg>
                     <span class="ms-3">Ayuda</span>
                  </x-sidebar-link>
               </li>
            </ul>
            <div class="flex items-center justify-between">
               <button type="button" id="user-dropdown-sidebar-button" data-dropdown-toggle="user-dropdown-sidebar"
                  class="flex items-center w-full p-1 text-sm text-zinc-900 rounded-lg dark:text-white hover:bg-zinc-800/5 dark:hover:bg-white/[7%] group">
                  <div class="flex items-center">
                     @if (Auth::user()->profile_photo_path)
                        <img class="w-8 h-8 rounded-lg object-cover mr-2"
                           src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}">
                     @else
                        <img class="w-8 h-8 rounded-lg object-cover mr-2"
                           src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&amp;color=fff&amp;background=6366f1"
                           alt="{{ Auth::user()->name }}">
                     @endif
                     <div>
                        <span class="text-base font-medium">{{ Auth::user()->name }}</span>
                     </div>
                  </div>
                  <svg class="w-4 h-4 ml-auto" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
               </button>

               <!-- Dropdown menu -->
               <div id="user-dropdown-sidebar"
                  class="z-10 hidden bg-white divide-y divide-zinc-100 rounded-lg shadow w-56 dark:bg-zinc-700 dark:divide-zinc-600">
                  <ul class="py-2 text-sm text-zinc-700 dark:text-zinc-200">
                     <li>
                        <a href="{{ route('profile.show') }}"
                           class="block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white">
                           {{ __('Mi Perfil') }}
                        </a>
                     </li>
                  </ul>
                  <div class="py-1">
                     <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <a href="{{ route('logout') }}"
                           class="block px-4 py-2 text-zinc-700 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white"
                           @click.prevent="$root.submit();">
                           {{ __('Cerrar Sesión') }}
                        </a>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </aside>
   <script>
      document.addEventListener('DOMContentLoaded', function () {
         const dropdownButton2 = document.getElementById('dropdown2');
         const dropdownMenu2 = document.getElementById('dropdown-menu2');

         dropdownButton2.addEventListener('click', function () {
            const isExpanded = dropdownMenu2.classList.contains('max-h-screen');

            if (isExpanded) {
               dropdownMenu2.classList.remove('max-h-screen', 'opacity-100');
               dropdownMenu2.classList.add('max-h-0', 'opacity-0');
            } else {
               dropdownMenu2.classList.remove('max-h-0', 'opacity-0');
               dropdownMenu2.classList.add('max-h-screen', 'opacity-100');
            }
         });
      });

      document.addEventListener('DOMContentLoaded', function () {
         const dropdownButton3 = document.getElementById('dropdown3');
         const dropdownMenu3 = document.getElementById('dropdown-menu3');

         dropdownButton3.addEventListener('click', function () {
            const isExpanded = dropdownMenu3.classList.contains('max-h-screen');

            if (isExpanded) {
               dropdownMenu3.classList.remove('max-h-screen', 'opacity-100');
               dropdownMenu3.classList.add('max-h-0', 'opacity-0');
            } else {
               dropdownMenu3.classList.remove('max-h-0', 'opacity-0');
               dropdownMenu3.classList.add('max-h-screen', 'opacity-100');
            }
         });
      });

      const userDropdownButton = document.getElementById('user-dropdown-sidebar-button');
      const userDropdownMenu = document.getElementById('user-dropdown-sidebar');

      if (userDropdownButton && userDropdownMenu) {
         userDropdownButton.addEventListener('click', function () {
            userDropdownMenu.classList.toggle('hidden');
         });

         // Cerrar el dropdown cuando se hace clic fuera de él
         document.addEventListener('click', function (event) {
            if (!userDropdownButton.contains(event.target) && !userDropdownMenu.contains(event.target)) {
               userDropdownMenu.classList.add('hidden');
            }
         });
      }
   </script>
</div>