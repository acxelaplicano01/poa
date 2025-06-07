<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsuarioTablaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear el usuario administrador
        $user = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'root', 
                'password' => bcrypt('12345678')
            ]
        );
        
        // Crear permisos específicos para los módulos si no existen
        $modulePermissions = [
            'acceso-configuracion',
            'acceso-planificacion',
            'configuracion.roles',
            'configuracion.usuarios',
            'planificacion.planificar'
        ];
        
        foreach ($modulePermissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }
        
        // Crear el rol super-admin
        $role = Role::firstOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web'],
            ['description' => 'Super Administrador']
        );
         
        // Asignar todos los permisos al rol
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
         
        // Asignar el rol al usuario
        $user->assignRole([$role->id]);
    }
}