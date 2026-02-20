<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TechoUesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('techo_ues')->truncate();

        $data = [
            ['id' => 1,  'monto' => '743713.00',  'idUE' => 1, 'idPoa' => 1,  'idGrupo' => 2,    'idFuente' => 1, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-06-19 23:02:47', 'updated_at' => '2023-06-19 23:02:47', 'deleted_at' => null],
            ['id' => 2,  'monto' => '1224665.00', 'idUE' => 1, 'idPoa' => 1,  'idGrupo' => 3,    'idFuente' => 1, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-06-19 21:18:43', 'updated_at' => '2023-06-19 21:18:43', 'deleted_at' => null],
            ['id' => 3,  'monto' => '3698572.00', 'idUE' => 1, 'idPoa' => 1,  'idGrupo' => 4,    'idFuente' => 1, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-06-19 21:18:43', 'updated_at' => '2023-06-19 21:18:43', 'deleted_at' => null],
            ['id' => 4,  'monto' => '100000.00',  'idUE' => 1, 'idPoa' => 1,  'idGrupo' => 1,    'idFuente' => 2, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-06-19 21:24:01', 'updated_at' => '2023-06-19 21:24:01', 'deleted_at' => null],
            ['id' => 5,  'monto' => '913746.00',  'idUE' => 1, 'idPoa' => 1,  'idGrupo' => 3,    'idFuente' => 2, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-06-19 21:24:01', 'updated_at' => '2023-06-19 21:24:01', 'deleted_at' => null],
            ['id' => 6,  'monto' => '965794.00',  'idUE' => 1, 'idPoa' => 1,  'idGrupo' => 2,    'idFuente' => 2, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-06-19 21:24:01', 'updated_at' => '2023-06-19 21:24:01', 'deleted_at' => null],
            ['id' => 7,  'monto' => '4505365.00', 'idUE' => 1, 'idPoa' => 1,  'idGrupo' => 4,    'idFuente' => 2, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-06-19 21:36:45', 'updated_at' => '2023-06-19 21:36:45', 'deleted_at' => null],
            ['id' => 9,  'monto' => '778382.00',  'idUE' => 1, 'idPoa' => 2,  'idGrupo' => 2,    'idFuente' => 1, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-07-10 16:40:54', 'updated_at' => '2024-03-19 17:23:56', 'deleted_at' => null],
            ['id' => 10, 'monto' => '941195.00',  'idUE' => 1, 'idPoa' => 2,  'idGrupo' => 3,    'idFuente' => 1, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-07-10 16:40:55', 'updated_at' => '2024-03-19 17:23:56', 'deleted_at' => null],
            ['id' => 11, 'monto' => '4037531.00', 'idUE' => 1, 'idPoa' => 2,  'idGrupo' => 4,    'idFuente' => 1, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-07-10 16:40:55', 'updated_at' => '2024-03-19 17:23:57', 'deleted_at' => null],
            ['id' => 12, 'monto' => '100000.00',  'idUE' => 1, 'idPoa' => 2,  'idGrupo' => 1,    'idFuente' => 2, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-07-10 16:40:55', 'updated_at' => '2024-03-19 17:23:57', 'deleted_at' => null],
            ['id' => 13, 'monto' => '4874166.00', 'idUE' => 1, 'idPoa' => 2,  'idGrupo' => 2,    'idFuente' => 2, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-07-10 16:40:55', 'updated_at' => '2024-03-19 17:23:57', 'deleted_at' => null],
            ['id' => 14, 'monto' => '952037.00',  'idUE' => 1, 'idPoa' => 2,  'idGrupo' => 3,    'idFuente' => 2, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-07-10 16:40:55', 'updated_at' => '2024-03-19 17:23:57', 'deleted_at' => null],
            ['id' => 15, 'monto' => '443070.00',  'idUE' => 1, 'idPoa' => 2,  'idGrupo' => 4,    'idFuente' => 2, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-07-10 16:40:55', 'updated_at' => '2024-03-19 17:23:57', 'deleted_at' => null],
            ['id' => 21, 'monto' => '200000.00',  'idUE' => 1, 'idPoa' => 2,  'idGrupo' => 5,    'idFuente' => 2, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-07-22 18:31:14', 'updated_at' => '2023-08-01 14:02:19', 'deleted_at' => null],
            ['id' => 22, 'monto' => '4269291.00', 'idUE' => 1, 'idPoa' => 2,  'idGrupo' => 4,    'idFuente' => 2, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-07-22 18:42:55', 'updated_at' => '2023-07-22 18:42:55', 'deleted_at' => null],
            ['id' => 23, 'monto' => '96000.00',   'idUE' => 1, 'idPoa' => 2,  'idGrupo' => 5,    'idFuente' => 2, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-08-08 20:52:49', 'updated_at' => '2024-03-19 17:23:58', 'deleted_at' => null],
            ['id' => 25, 'monto' => '300000.00',  'idUE' => 2, 'idPoa' => 3,  'idGrupo' => 2,    'idFuente' => 1, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-08-21 20:25:55', 'updated_at' => '2023-08-21 20:25:55', 'deleted_at' => null],
            ['id' => 26, 'monto' => '300000.00',  'idUE' => 2, 'idPoa' => 3,  'idGrupo' => 3,    'idFuente' => 1, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-08-21 20:25:55', 'updated_at' => '2023-08-21 20:25:55', 'deleted_at' => null],
            ['id' => 27, 'monto' => '300000.00',  'idUE' => 2, 'idPoa' => 3,  'idGrupo' => 4,    'idFuente' => 1, 'created_by' => null, 'updated_by' => null, 'deleted_by' => null, 'created_at' => '2023-08-21 20:25:55', 'updated_at' => '2023-08-21 20:25:55', 'deleted_at' => null],
            ['id' => 28, 'monto' => '100000.00',  'idUE' => 1, 'idPoa' => 4,  'idGrupo' => 1,    'idFuente' => 2, 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => null, 'created_at' => '2024-06-19 13:49:42', 'updated_at' => '2024-06-19 13:49:42', 'deleted_at' => null],
            ['id' => 29, 'monto' => '778382.00',  'idUE' => 1, 'idPoa' => 4,  'idGrupo' => 2,    'idFuente' => 1, 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => null, 'created_at' => '2024-06-19 13:49:42', 'updated_at' => '2024-06-19 13:49:42', 'deleted_at' => null],
            ['id' => 30, 'monto' => '4971702.00', 'idUE' => 1, 'idPoa' => 4,  'idGrupo' => 2,    'idFuente' => 2, 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => null, 'created_at' => '2024-06-19 13:49:42', 'updated_at' => '2024-06-19 13:49:42', 'deleted_at' => null],
            ['id' => 31, 'monto' => '710146.00',  'idUE' => 1, 'idPoa' => 4,  'idGrupo' => 3,    'idFuente' => 1, 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => null, 'created_at' => '2024-06-19 13:49:42', 'updated_at' => '2024-06-19 13:49:42', 'deleted_at' => null],
            ['id' => 32, 'monto' => '912501.00',  'idUE' => 1, 'idPoa' => 4,  'idGrupo' => 3,    'idFuente' => 2, 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => null, 'created_at' => '2024-06-19 13:49:42', 'updated_at' => '2024-06-19 13:49:42', 'deleted_at' => null],
            ['id' => 33, 'monto' => '76949.00',   'idUE' => 1, 'idPoa' => 4,  'idGrupo' => 4,    'idFuente' => 1, 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => null, 'created_at' => '2024-06-19 13:49:42', 'updated_at' => '2024-06-19 13:49:42', 'deleted_at' => null],
            ['id' => 34, 'monto' => '478070.00',  'idUE' => 1, 'idPoa' => 4,  'idGrupo' => 4,    'idFuente' => 2, 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => null, 'created_at' => '2024-06-19 13:49:43', 'updated_at' => '2024-06-19 13:49:43', 'deleted_at' => null],
            ['id' => 36, 'monto' => '300000.00',  'idUE' => 1, 'idPoa' => 4,  'idGrupo' => 5,    'idFuente' => 2, 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => null, 'created_at' => '2024-06-19 13:49:43', 'updated_at' => '2024-06-19 13:49:43', 'deleted_at' => null],
            ['id' => 37, 'monto' => '1565477.00', 'idUE' => 1, 'idPoa' => 22, 'idGrupo' => null, 'idFuente' => 1, 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => null, 'created_at' => '2024-07-01 12:19:30', 'updated_at' => '2024-07-01 16:06:49', 'deleted_at' => null],
            ['id' => 38, 'monto' => '1490571.00', 'idUE' => 1, 'idPoa' => 22, 'idGrupo' => null, 'idFuente' => 2, 'created_by' => 32,   'updated_by' => 32,   'deleted_by' => null, 'created_at' => '2024-07-01 12:19:30', 'updated_at' => '2024-07-01 16:06:49', 'deleted_at' => null],
            ['id' => 39, 'monto' => '200000.00',  'idUE' => 1, 'idPoa' => 23, 'idGrupo' => null, 'idFuente' => 1, 'created_by' => 1,    'updated_by' => 1,    'deleted_by' => null, 'created_at' => '2024-09-10 10:22:25', 'updated_at' => '2024-09-10 10:22:25', 'deleted_at' => null],
            ['id' => 40, 'monto' => '100000.00',  'idUE' => 1, 'idPoa' => 23, 'idGrupo' => null, 'idFuente' => 2, 'created_by' => 1,    'updated_by' => 1,    'deleted_by' => null, 'created_at' => '2024-09-10 10:22:25', 'updated_at' => '2024-09-10 10:22:25', 'deleted_at' => null],
            ['id' => 41, 'monto' => '300000.00',  'idUE' => 1, 'idPoa' => 23, 'idGrupo' => null, 'idFuente' => 3, 'created_by' => 1,    'updated_by' => 1,    'deleted_by' => null, 'created_at' => '2024-09-10 10:22:25', 'updated_at' => '2024-09-10 10:22:25', 'deleted_at' => null],
            ['id' => 42, 'monto' => '9989.00',    'idUE' => 1, 'idPoa' => 24, 'idGrupo' => null, 'idFuente' => 2, 'created_by' => 1,    'updated_by' => 1,    'deleted_by' => null, 'created_at' => '2025-07-01 13:48:06', 'updated_at' => '2025-07-01 13:48:06', 'deleted_at' => null],
        ];

        DB::table('techo_ues')->insert($data);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $count = DB::table('techo_ues')->count();
        $this->command->info("TechoUesSeeder: {$count} registros insertados.");
    }
}
