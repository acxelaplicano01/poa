<div>

@php
function isActiveModule($module)
{
   static $routeMap = null;

   // Generar el routeMap solo una vez
   if ($routeMap === null) {
      $routeMap = [];
      $allModules = config('rutas');

      if (is_array($allModules)) {
         foreach ($allModules as $moduleName => $moduleLinks) {
            $routeMap[$moduleName] = [];

            if (is_array($moduleLinks)) {
               foreach ($moduleLinks as $link) {
                  if (isset($link['route'])) {
                     $routeMap[$moduleName][] = $link['route'];
                  }
               }
            }
         }
      }
   }

   $currentRoute = request()->route() ? request()->route()->getName() : '';

   return isset($routeMap[$module]) && in_array($currentRoute, $routeMap[$module]);
}

function hasModuleAccess($module)
{
   // Obtener usuario actual
   $user = auth()->user();
   if (!$user) return false;
   
   // Verificar si es super-admin (siempre tiene acceso)
   if ($user->hasRole('super-admin')) return true;
   
   // Verificar permiso específico para acceder al módulo
   $permission = "acceso-{$module}";
   return $user->can($permission);
}

// Nueva función para verificar permisos específicos dentro de un módulo
function hasModulePermission($modulePermission) 
{
   // Obtener usuario actual
   $user = auth()->user();
   if (!$user) return false;
   
   // Si es super-admin, siempre tiene permiso
   if ($user->hasRole('super-admin')) return true;
   
   // Dividir el permiso para saber a qué módulo pertenece
   $parts = explode('.', $modulePermission);
   if (count($parts) < 2) return false;
   
   $module = $parts[0];
   
   // Primero verificamos si tiene acceso al módulo principal
   $moduleAccess = "acceso-{$module}";
   if (!$user->can($moduleAccess)) return false;
   
   // Si tiene acceso al módulo, entonces verificamos el permiso específico
   return $user->can($modulePermission);
}

// Función de ayuda para mostrar los permisos en la vista (solo durante depuración)
function getDebugPermissions($userID = null) {
    $user = $userID ? \App\Models\User::find($userID) : auth()->user();
    if (!$user) return [];
    
    return [
        'id' => $user->id,
        'name' => $user->name,
        'roles' => $user->getRoleNames()->toArray(),
        'permissions' => $user->getAllPermissions()->pluck('name')->toArray()
    ];
}
@endphp
   <aside id="logo-sidebar"
      class="fixed top-0 left-0 z-40 w-64 h-screen pt-4 transition-transform -translate-x-full bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700 border-r sm:translate-x-0"
      aria-label="Sidebar">
      <div class="h-full px-3 pb-4 overflow-y-auto barra dark:barra bg-zinc-50 dark:bg-zinc-900 flex flex-col">
         <ul class="space-y-2 font-medium flex-grow">
            <div class="flex items-center justify-start rtl:justify-end mb-6">
               <a href="/dashboard" class="flex ms-2 md:me-24">
                  <!-- Logo para modo claro -->
                  <img src="{{ asset('Logo/poav2_grey.png') }}" alt="Logo" height="80px" width="80px" class="dark:hidden" />
         
                  <!-- Logo para modo oscuro -->
                  <img src="{{ asset('Logo/poav2.webp') }}" alt="Logo" height="80px" width="80px" class="hidden dark:block" />
         
                  <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white"></span>
               </a>
            </div>
         
            {{-- El dashboard siempre es visible --}}
            <li>
               <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
                  class="flex items-center p-1 rounded-lg group">
                  <x-activeIcons :active="request()->routeIs('dashboard')" class="w-5 h-5 ml-2" aria-hidden="true"
                     xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                  </x-activeIcons>
                  <span class="ms-3">Inicio</span>
               </x-sidebar-link>
            </li>
         
            @if(hasModuleAccess('planificacion'))
            <li>
               <x-sidebar-link href="{{ route('planificar') }}" :active="isActiveModule('planificacion')"
                 class="flex items-center p-1 rounded-lg group">
                 <x-activeIcons :active="isActiveModule('planificacion')" class="w-5 h-5 ml-2" aria-hidden="true"
                   xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                   <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M10 3v4a1 1 0 0 1-1 1H5m4 10v-2m3 2v-6m3 6v-3m4-11v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />
                 </x-activeIcons>
                 <span class="ms-3">Planificación</span>
               </x-sidebar-link>
            </li>
         @endif
         
            @if(hasModuleAccess('gestion'))
            <li>
               <x-sidebar-link href="{{ route('dashboard') }}" :active="isActiveModule('gestion')"
                 class="flex items-center p-1 rounded-lg group">
                 <x-activeIcons :active="isActiveModule('gestion')" class="w-5 h-5 ml-2" aria-hidden="true"
                   xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                   <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M9.143 4H4.857A.857.857 0 0 0 4 4.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 10 9.143V4.857A.857.857 0 0 0 9.143 4Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 20 9.143V4.857A.857.857 0 0 0 19.143 4Zm-10 10H4.857a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286A.857.857 0 0 0 9.143 14Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286a.857.857 0 0 0-.857-.857Z" />
                 </x-activeIcons>
                 <span class="ms-3">Gestión Administrativa</span>
               </x-sidebar-link>
            </li>
         @endif
         
            @if(hasModuleAccess('reportes'))
            <li>
               <x-sidebar-link href="{{ route('dashboard') }}" :active="isActiveModule('reportes')"
                 class="flex items-center p-1 rounded-lg group">
                 <x-activeIcons :active="isActiveModule('reportes')" class="w-5 h-5 ml-2" aria-hidden="true"
                   xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                   <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
                   <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z" />
                 </x-activeIcons>
                 <span class="ms-3">Reportes</span>
               </x-sidebar-link>
            </li>
         @endif
         
            @if(hasModuleAccess('consolas'))
            <li>
               <x-sidebar-link href="{{ route('dashboard') }}" :active="isActiveModule('consolas')"
                 class="flex items-center p-1 rounded-lg group">
                 <x-activeIcons :active="isActiveModule('consolas')" class="w-5 h-5 ml-2" aria-hidden="true"
                   xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                   <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M13.6 16.733c.234.269.548.456.895.534a1.4 1.4 0 0 0 1.75-.762c.172-.615-.446-1.287-1.242-1.481-.796-.194-1.41-.861-1.241-1.481a1.4 1.4 0 0 1 1.75-.762c.343.077.654.26.888.524m-1.358 4.017v.617m0-5.939v.725M4 15v4m3-6v6M6 8.5 10.5 5 14 7.5 18 4m0 0h-3.5M18 4v3m2 8a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z" />
                 </x-activeIcons>
                 <span class="ms-3">Consolas</span>
               </x-sidebar-link>
            </li>
         @endif
            <!-- aquí más enlaces si es necesario -->
         </ul>

         <!-- Componente de perfil al final del sidebar -->
         <div class="mt-auto  border-zinc-200 dark:border-zinc-700 pt-4">
            <ul class="space-y-2 font-medium flex-grow mb-6">
               @if(hasModuleAccess('configuracion'))
               <li>
                 <x-sidebar-link href="{{ route('roles') }}" :active="isActiveModule('configuracion')"
                   class="flex items-center p-1 rounded-lg group">
                   <x-activeIcons :active="isActiveModule('configuracion')" class="w-5 h-5 ml-2" aria-hidden="true"
                     xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 13v-2a1 1 0 0 0-1-1h-.757l-.707-1.707.535-.536a1 1 0 0 0 0-1.414l-1.414-1.414a1 1 0 0 0-1.414 0l-.536.535L14 4.757V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v.757l-1.707.707-.536-.535a1 1 0 0 0-1.414 0L4.929 6.343a1 1 0 0 0 0 1.414l.536.536L4.757 10H4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h.757l.707 1.707-.535.536a1 1 0 0 0 0 1.414l1.414 1.414a1 1 0 0 0 1.414 0l.536-.535 1.707.707V20a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-.757l1.707-.708.536.536a1 1 0 0 0 1.414 0l1.414-1.414a1 1 0 0 0 0-1.414l-.535-.536.707-1.707H20a1 1 0 0 0 1-1Z" />
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                   </x-activeIcons>
                   <span class="ms-3">Configuración</span>
                 </x-sidebar-link>
               </li>
            @endif
               <li>
                  <x-sidebar-link href="https://chat.whatsapp.com/CnEA4qNlOBoLK1Hh8NKsKI"
                     class="flex items-center p-1 rounded-lg group">
                     <x-activeIcons class="w-5 h-5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                     </x-activeIcons>
                     <span class="ms-3">Ayuda</span>
                  </x-sidebar-link>
               </li>
               <!-- Añadir aquí más enlaces si es necesario -->
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
                        <span class=" text-base font-medium">{{ Auth::user()->name }}</span>
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
                        <x-dropdown-link href="{{ route('logout') }}"
                           class="block px-4 py-2 text-zinc-700 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white"
                           @click.prevent="$root.submit();">
                           {{ __('Cerrar Sesión') }}
                        </x-dropdown-link>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      {{-- Solo mostrar en modo desarrollo (eliminar en producción) --}}
@if(app()->environment('local') && auth()->check())
<div class="fixed bottom-0 right-0 bg-white dark:bg-zinc-800 p-4 shadow-lg text-xs opacity-50 hover:opacity-100 transition-opacity" style="max-width: 300px; max-height: 200px; overflow: auto; z-index: 9999;">
    <h6 class="font-bold mb-1">Debug Permisos:</h6>
    <p>Usuario: {{ auth()->user()->name }} ({{ auth()->id() }})</p>
    <p>Roles: {{ implode(', ', auth()->user()->getRoleNames()->toArray()) }}</p>
    <p>Permisos:</p>
    <ul class="list-disc pl-4">
        @foreach(auth()->user()->getAllPermissions()->pluck('name') as $perm)
            <li>{{ $perm }}</li>
        @endforeach
    </ul>
    <button onclick="document.location.reload(true)" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-xs">
        Recargar sin caché
    </button>
</div>
@endif
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