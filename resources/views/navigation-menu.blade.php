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
         
         // Verificar si hay una ruta individual del item
         if (isset($item['route']) && $currentRoute == $item['route']) {
            return true;
         }
      }
   }

   // Verificar rutas anidadas (como roles.create, roles.edit, etc.)
   if (strpos($currentRoute, '.') !== false) {
      $routeParts = explode('.', $currentRoute);
      $actionLabels = ['create', 'edit', 'show', 'delete'];
      
      // Si la última parte es una acción conocida
      $lastPart = end($routeParts);
      if (in_array($lastPart, $actionLabels)) {
         // Construir la ruta padre (quitar la última parte)
         $parentRouteParts = array_slice($routeParts, 0, -1);
         $parentRoute = implode('.', $parentRouteParts);
         
         // Buscar la ruta padre en la configuración del módulo
         if (isset($moduleConfig['items']) && is_array($moduleConfig['items'])) {
            foreach ($moduleConfig['items'] as $item) {
               // Verificar en el array de rutas
               if (isset($item['routes']) && is_array($item['routes']) && in_array($parentRoute, $item['routes'])) {
                  return true;
               }
               // Verificar la ruta individual
               if (isset($item['route']) && $item['route'] === $parentRoute) {
                  return true;
               }
            }
         }
      }
   }

   return false;
}

function hasModuleAccess($module)
{
   $user = auth()->user();
   if (!$user)
      return false;

   // Super admin tiene acceso a todo
   if ($user->hasRole('super-admin'))
      return true;

   // Verificar la configuración del módulo
   $moduleConfig = config('rutas.' . $module, []);

   // Verificar permiso general del módulo
   $permiso = "acceso-{$module}";
   $hasModuleAccess = $user->can($permiso);

   // Si no tiene acceso al módulo, verificar si hay al menos un item always_visible
   if (!$hasModuleAccess) {
      if (isset($moduleConfig['items']) && is_array($moduleConfig['items'])) {
         foreach ($moduleConfig['items'] as $item) {
            if (isset($item['always_visible']) && $item['always_visible']) {
               return true; // Mostrar el módulo si tiene al menos un item always_visible
            }
         }
      }
      return false; // No tiene acceso al módulo ni hay items always_visible
   }

   return true; // Tiene acceso al módulo
}

function findFirstAccessibleRoute($module)
{
   $user = auth()->user();
   $moduleConfig = config('rutas.' . $module, []);

   // Si es super-admin o no hay config, usar la ruta principal
   if (!$user || !isset($moduleConfig['items']) || empty($moduleConfig['items'])) {
      return isset($moduleConfig['route']) ? $moduleConfig['route'] : 'dashboard';
   }

   if ($user->hasRole('super-admin')) {
      return $moduleConfig['route'];
   }

   // Primero buscar en items always_visible
   foreach ($moduleConfig['items'] as $item) {
      if (isset($item['always_visible']) && $item['always_visible'] && isset($item['route'])) {
         return $item['route'];
      }
   }

   // Luego buscar en items donde tenga permiso
   foreach ($moduleConfig['items'] as $item) {
      // Si no tiene permisos, es accesible
      if (!isset($item['permisos']) || empty($item['permisos'])) {
         return $item['route'];
      }

      // Verificar si tiene alguno de los permisos requeridos
      foreach ((array) $item['permisos'] as $permiso) {
         if ($user->can($permiso)) {
            return $item['route'];
         }
      }
   }

   // Si ningún item es accesible, retornar la ruta principal como fallback
   return $moduleConfig['route'];
}

// Obtener la configuración del menú
$moduleConfig = config('rutas', []);
   @endphp
   
   <aside id="logo-sidebar"
      class="fixed top-0 left-0 z-40 w-64 h-screen pt-4 transition-transform -translate-x-full bg-white dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700 border-r sm:translate-x-0"
      aria-label="Sidebar">
      <div class="h-full px-3 pb-4 overflow-y-auto barra dark:barra bg-white dark:bg-zinc-900 flex flex-col">
         <ul class="space-y-2 font-medium flex-grow">
            <div class="mb-6">
                  <x-application-logo />
            </div>
            {{-- Generar menú principal dinámicamente - SOLO MÓDULOS --}}
            @foreach($moduleConfig as $moduleKey => $moduleData)
               @if(!isset($moduleData['footer']) || !$moduleData['footer'])
                  @if(hasModuleAccess($moduleKey))
                     <li>
                        <x-sidebar-link href="{{ route(findFirstAccessibleRoute($moduleKey)) }}" :active="isActiveModule($moduleKey)">
                           <x-activeIcons :active="isActiveModule($moduleKey)">
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
                        <x-sidebar-link href="{{ route(findFirstAccessibleRoute($moduleKey)) }}" :active="isActiveModule($moduleKey)">
                           <x-activeIcons :active="isActiveModule($moduleKey)">
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
                        <img class="w-8 h-8 rounded-lg object-cover mr-2" src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
                           alt="{{ Auth::user()->name }}">
                     @else
                        <div class="flex-shrink-0 mr-3">
                           <div class="h-8 w-8 rounded-lg bg-indigo-700 flex items-center justify-center">
                              <span class="text-white text-xs font-bold">{{ substr(Auth::user()->name ?? 'U', 0, 2) }}</span>
                           </div>
                        </div>
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
                           class="group flex items-center gap-3 px-4 py-2 text-zinc-500 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white w-full">
                           <svg
                              class="w-5 h-5 text-zinc-500 dark:text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-white transition duration-100 ease-in-out"
                              aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                           </svg>
                           <span class="group-hover:text-zinc-900 dark:group-hover:text-white transition duration-100 ease-in-out">
                               {{ __('Mi Perfil') }}
                           </span>
                        </a>
                     </li>
                  </ul>
                  <div class="py-1">
                     <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <a href="{{ route('logout') }}"
                           class="group flex items-center gap-3 px-4 py-2 text-red-800 dark:text-zinc-400 hover:bg-red-100 dark:hover:bg-zinc-600 dark:hover:text-white w-full"
                           @click.prevent="$root.submit();">
                           <svg
                              class="w-5 h-5 text-red-800 dark:text-zinc-400 group-hover:text-red-600 dark:group-hover:text-white transition duration-100 ease-in-out"
                              aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2" />
                           </svg>
                           <span class="group-hover:text-red-600 dark:group-hover:text-white transition duration-100 ease-in-out">
                              {{ __('Cerrar Sesión') }}
                           </span>
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