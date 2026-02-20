<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoasSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('poas')->truncate();

        DB::table('poas')->insert([
            ['id' => 1,  'name' => '2023', 'anio' => '2023', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2023-06-19 20:11:56', 'updated_at' => '2024-03-12 13:40:32', 'deleted_at' => null,                  'created_by' => null, 'updated_by' => null, 'deleted_by' => null],
            ['id' => 2,  'name' => '2024', 'anio' => '2024', 'activo' => 1, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2023-07-10 16:40:54', 'updated_at' => '2024-01-30 15:43:30', 'deleted_at' => null,                  'created_by' => null, 'updated_by' => null, 'deleted_by' => null],
            ['id' => 3,  'name' => '2024', 'anio' => '2024', 'activo' => 1, 'idInstitucion' => 1, 'idUE' => 2, 'created_at' => '2023-08-16 23:37:06', 'updated_at' => '2023-08-21 20:46:01', 'deleted_at' => null,                  'created_by' => null, 'updated_by' => null, 'deleted_by' => null],
            ['id' => 4,  'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-06-19 13:49:42', 'updated_at' => '2024-07-01 11:18:21', 'deleted_at' => '2024-07-01 11:18:21', 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => 32],
            ['id' => 5,  'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:46:32', 'updated_at' => '2024-07-01 11:51:41', 'deleted_at' => '2024-07-01 11:51:41', 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => 32],
            ['id' => 6,  'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:46:35', 'updated_at' => '2024-07-01 11:48:20', 'deleted_at' => '2024-07-01 11:48:20', 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => 32],
            ['id' => 7,  'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:46:37', 'updated_at' => '2024-07-01 11:48:16', 'deleted_at' => '2024-07-01 11:48:16', 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => 32],
            ['id' => 8,  'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:46:37', 'updated_at' => '2024-07-01 11:48:12', 'deleted_at' => '2024-07-01 11:48:12', 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => 32],
            ['id' => 9,  'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:46:46', 'updated_at' => '2024-07-01 11:48:09', 'deleted_at' => '2024-07-01 11:48:09', 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => 32],
            ['id' => 10, 'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:46:47', 'updated_at' => '2024-07-01 11:48:05', 'deleted_at' => '2024-07-01 11:48:05', 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => 32],
            ['id' => 11, 'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:46:47', 'updated_at' => '2024-07-01 11:48:01', 'deleted_at' => '2024-07-01 11:48:01', 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => 32],
            ['id' => 12, 'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:46:47', 'updated_at' => '2024-07-01 11:47:57', 'deleted_at' => '2024-07-01 11:47:57', 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => 32],
            ['id' => 13, 'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:46:47', 'updated_at' => '2024-07-01 11:47:52', 'deleted_at' => '2024-07-01 11:47:52', 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => 32],
            ['id' => 14, 'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:46:48', 'updated_at' => '2024-07-01 11:47:48', 'deleted_at' => '2024-07-01 11:47:48', 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => 32],
            ['id' => 15, 'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:46:48', 'updated_at' => '2024-07-01 11:47:44', 'deleted_at' => '2024-07-01 11:47:44', 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => 32],
            ['id' => 16, 'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:46:48', 'updated_at' => '2024-07-01 11:47:39', 'deleted_at' => '2024-07-01 11:47:39', 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => 32],
            ['id' => 17, 'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:46:48', 'updated_at' => '2024-07-01 11:47:34', 'deleted_at' => '2024-07-01 11:47:34', 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => 32],
            ['id' => 18, 'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:46:48', 'updated_at' => '2024-07-01 11:47:30', 'deleted_at' => '2024-07-01 11:47:30', 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => 32],
            ['id' => 19, 'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:53:51', 'updated_at' => '2024-07-01 12:19:10', 'deleted_at' => '2024-07-01 12:19:10', 'created_by' => 1,    'updated_by' => 1,    'deleted_by' => 1],
            ['id' => 20, 'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:53:52', 'updated_at' => '2024-07-01 11:54:19', 'deleted_at' => '2024-07-01 11:54:19', 'created_by' => 1,    'updated_by' => 1,    'deleted_by' => 1],
            ['id' => 21, 'name' => '2025', 'anio' => '2025', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 11:53:53', 'updated_at' => '2024-07-01 11:54:14', 'deleted_at' => '2024-07-01 11:54:14', 'created_by' => 1,    'updated_by' => 1,    'deleted_by' => 1],
            ['id' => 22, 'name' => '2025', 'anio' => '2025', 'activo' => 1, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-07-01 12:19:30', 'updated_at' => '2024-07-02 12:25:58', 'deleted_at' => null,                  'created_by' => 1,    'updated_by' => 32,   'deleted_by' => null],
            ['id' => 23, 'name' => '2022', 'anio' => '2022', 'activo' => 1, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2024-09-10 10:22:25', 'updated_at' => '2024-09-10 10:22:29', 'deleted_at' => null,                  'created_by' => 1,    'updated_by' => 1,    'deleted_by' => null],
            ['id' => 24, 'name' => '2029', 'anio' => '2029', 'activo' => 0, 'idInstitucion' => 1, 'idUE' => 1, 'created_at' => '2025-07-01 13:48:06', 'updated_at' => '2025-07-01 13:49:06', 'deleted_at' => '2025-07-01 13:49:06', 'created_by' => 1,    'updated_by' => 1,    'deleted_by' => 1],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
