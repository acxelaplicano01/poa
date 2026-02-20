<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('model_has_roles')->where('model_type', 'App\\Models\\User')->delete();
        DB::table('users')->truncate();

        $now = now();

        // Datos completos tomados de db_poa.Usuarios
        // [email, name, password (hash original), idEmpleado, idRol]
        $usuarios = [
            ['email' => 'cjso0323@gmail.com',              'name' => 'root',                'password' => '$2y$08$HFG.dAizpY00/Oz3t3EE.uAWoi6Ry1lgLJQAX6SOsdDbwimLb/6P.', 'idEmpleado' => 1,   'idRol' => 1],
            ['email' => 'admin@gmail.com',                  'name' => 'admin',               'password' => '$2y$08$xt4/Q/wdv2eRqyiRVhgjQ.m40nyDxIuoorWPs.vrROZGyWrnFrH8i', 'idEmpleado' => 2,   'idRol' => 2],
            ['email' => 'planificador@gmail.com',           'name' => 'depto',               'password' => '$2y$08$Ky6XmSWHW.joxAVA2GY11eXsNuj1bUfLr/YTcAGrORrEtE/g2.hPi', 'idEmpleado' => 3,   'idRol' => 3],
            ['email' => 'dorian.ordonez@unah.edu.hn',      'name' => 'dorian.ordonez',      'password' => '$2y$08$e1OMLckMDfLREc3otC/aSu20sWOoYdgh60qFDiZtFfmxfEtx29hGq', 'idEmpleado' => 112, 'idRol' => 3],
            ['email' => 'liliam.marroquin@unah.edu.hn',    'name' => 'liliam.marroquin',    'password' => '$2y$08$ZcZizG0p0fAo7MH2Hj1QHeScAefHCFiPCsIEkQLiqr8.ipzShnN7u', 'idEmpleado' => 4,   'idRol' => 3],
            ['email' => 'edgar.carranza@unah.edu.hn',      'name' => 'edgar.carranza',      'password' => '$2y$08$zDHQv024Mfu8JJcJdvO3D.2KkKBAMxYoqqccM77ktLVo7.biccuOC', 'idEmpleado' => 8,   'idRol' => 3],
            ['email' => 'luz.baca@unah.edu.hn',            'name' => 'luz.baca',            'password' => '$2y$08$17gKRYf6B1yjaF6FFsQzR.X1pOE7G3LFE/ChLFJZ7eSyoPtd5Ih5O', 'idEmpleado' => 98,  'idRol' => 3],
            ['email' => 'javier.mendoza@unah.edu.hn',      'name' => 'javier.mendoza',      'password' => '$2y$08$5ocZifhLC/8z/mLadutYbueZ7co.n50qh/F5VpQ.vgIOrKf3MagVy', 'idEmpleado' => 93,  'idRol' => 3],
            ['email' => 'carla.moncada@unah.edu.hn',       'name' => 'carla.moncada',       'password' => '$2y$08$vdDCaK/qJ4sYa8y.eAjs8ONLSI2VnNP8jb8Hppqby5JKdHAW7Pu8i', 'idEmpleado' => 28,  'idRol' => 3],
            ['email' => 'karen.castro@unah.edu.hn',        'name' => 'karen.castro',        'password' => '$2y$08$brjwWDDADKpgrG43CBxNNOvY0n/IHM3qSZbLw.pJBQyjsweEzbJE2', 'idEmpleado' => 88,  'idRol' => 1],
            ['email' => 'vilma.salinas@unah.edu.hn',       'name' => 'vilma.salinas',       'password' => '$2y$08$a5EfYJr6PhXz0a6YSdaM2.admuxf8BiHULjvhLdrFRuMuhmy7Yw5G', 'idEmpleado' => 58,  'idRol' => 3],
            ['email' => 'elvin.mejia@unah.edu.hn',         'name' => 'elvin.mejia',         'password' => '$2y$08$57ebrEy7oISNGk9LQWQ2zOo7/yN.3hwQntY6EWtx4T5ZCb9P703z.', 'idEmpleado' => 34,  'idRol' => 6],
            ['email' => 'wilson.villanueva@unah.edu.hn',   'name' => 'wilson.villanueva',   'password' => '$2y$08$p1ShwVdjJ6OPoQnvHCQnnexTqhNqvrfG5L2YWLG1o4wzhGNEtKJtS', 'idEmpleado' => 89,  'idRol' => 3],
            ['email' => 'lizeth.zuniga@unah.edu.hn',       'name' => 'lizeth.zuniga',       'password' => '$2y$08$LHOp4mZlRXrssgSws/SpHupz6562Dh589qDi.VFr9rkLnrWH5LVqC', 'idEmpleado' => 21,  'idRol' => 3],
            ['email' => 'carlos.aceituno@unah.edu.hn',     'name' => 'carlos.aceituno',     'password' => '$2y$08$niADL25W9tUTCYaEjK0lNeFatj2XDEz7nISXhowebxq441XMlSNje', 'idEmpleado' => 92,  'idRol' => 3],
            ['email' => 'bryan.bonilla@unah.edu.hn',       'name' => 'bryan.bonilla',       'password' => '$2y$08$sO1CBNdA5K8oAeoqIpy3Kuao1srZK.vsuo/3D08As2JytyPM4201.',  'idEmpleado' => 57,  'idRol' => 3],
            ['email' => 'jose.avila@unah.edu.hn',          'name' => 'jose.avila',          'password' => '$2y$08$K5a70jM8Mb7/43sU.PiQceZmRHscuR4YK5GbgqHms3jNU83Gvj46y', 'idEmpleado' => 14,  'idRol' => 3],
            ['email' => 'candido.flores@unah.edu.hn',      'name' => 'candido.flores',      'password' => '$2y$08$8RhEjWs0t35NLJrg/7EaF.cmtD24AFeYlPf9oK7nFEKoPoHOilgg.',  'idEmpleado' => 56,  'idRol' => 3],
            ['email' => 'keila.euceda@unah.edu.hn',        'name' => 'keila.euceda',        'password' => '$2y$08$JfVja6V7oC4lJC2FgcmAueuhnUVwuyd6E7RV3kGAlqO4G3xjODLcS', 'idEmpleado' => 110, 'idRol' => 3],
            ['email' => 'eimy.lopez@unah.edu.hn',          'name' => 'eimy.lopez',          'password' => '$2y$08$OVKyh4KT5a.zQvFprEgWWOGxphysyI8lEOvHmKaAgOrmfwCMGe2Te', 'idEmpleado' => 91,  'idRol' => 3],
            ['email' => 'francia.portillo@unah.edu.hn',    'name' => 'francia.portillo',    'password' => '$2y$08$gjFFEebUzyk/nfArjzH.1e9hYPEh17F4AYzd0qdCpqw8OxhuLd7DS', 'idEmpleado' => 45,  'idRol' => 3],
            ['email' => 'abel.carrasco@unah.edu.hn',       'name' => 'abel.carrasco',       'password' => '$2y$08$32KQg83iNs.Lfc5oCxuwa.U1oEq.HPzVkejtiViFEjUzURUyjek3q', 'idEmpleado' => 100, 'idRol' => 3],
            ['email' => 'sergio.manzanares@unah.edu.hn',   'name' => 'sergio.manzanares',   'password' => '$2y$08$OAPLqWnElcwoXtShoRemdeFvsuoLkUJuZWjM2vuMR3TInCpSfYBEO', 'idEmpleado' => 25,  'idRol' => 3],
            ['email' => 'jessica.avila@unah.edu.hn',       'name' => 'jessica.avila',       'password' => '$2y$08$J15GiHWgEDdPIaU/tdqrneOkaAm82TJQQiegIWMrdIRPZr.tSQNem', 'idEmpleado' => 33,  'idRol' => 3],
            ['email' => 'maria.perez@unah.edu.hn',         'name' => 'maria.perez',         'password' => '$2y$08$apCO1.8Q.FGsI6r0QsnOpefozCjDbVFZt1abEToVNEhqOLDQz9E06', 'idEmpleado' => 107, 'idRol' => 3],
            ['email' => 'robertomartinez@unah.edu.hn',     'name' => 'robertomartinez',     'password' => '$2y$08$z81vSE2hpXZXiJNkbn/vrOkijMIGoRshBE65IyEzRCTw0u/gW0OIm', 'idEmpleado' => 7,   'idRol' => 3],
            ['email' => 'danilo.manzanares@unah.edu.hn',   'name' => 'danilo.manzanares',   'password' => '$2y$08$9dCpyXPdBIkVWATkTI8f4.EGOdd/PTPBeX4aDO1GgpzBQWqo4VX72', 'idEmpleado' => 118, 'idRol' => 3],
            ['email' => 'alejandra.valeriano@unah.edu.hn', 'name' => 'alejandra.valeriano', 'password' => '$2y$08$gt9Mo8ADVyjE1/AGMwr28uE22MEoucRTCoT7cAIW/gHmcw9jjao6S',  'idEmpleado' => 97,  'idRol' => 3],
            ['email' => 'martha.rodriguez@unah.edu.hn',    'name' => 'martha.rodriguez',    'password' => '$2y$08$7eJgqkR92sem3wVX1WWOJ.YW68UUnNuIV5YKrEAR6Y1OHcLGyUAq2', 'idEmpleado' => 87,  'idRol' => 3],
            ['email' => 'jorge.villibord@unah.edu.hn',     'name' => 'jorge.villibord',     'password' => '$2y$08$hiNbVwHRCzot4dhtkjI18ef9KdqHeK6c2bDnRQChehIrTHqyaeUJC', 'idEmpleado' => 94,  'idRol' => 3],
            ['email' => 'vreyes@unah.edu.hn',              'name' => 'vreyes',              'password' => '$2y$08$gmvjkpv7A3jMRtfbb3MBdeKan7Af4BacAUJVTvTIyOoHkhCC8otQi', 'idEmpleado' => 95,  'idRol' => 3],
            ['email' => 'jmendoza@unah.edu.hn',            'name' => 'jmendoza',            'password' => '$2y$08$3n1Hav9kHKN9elmz7BJQt.PJLUY3/7lQkdvuXzS42D/p8HaT/2Dk2', 'idEmpleado' => 82,  'idRol' => 2],
            ['email' => 'dennis.alvarenga@unah.edu.hn',    'name' => 'dennis.alvarenga',    'password' => '$2y$08$ybHtu8.nI/WLsKr1r2.2mOZcOEsIcF8Sg94iHy4dvB6LQrbK1QeTm', 'idEmpleado' => 102, 'idRol' => 3],
            ['email' => 'maximiliano.estrada@unah.edu.hn', 'name' => 'maximiliano.estrada', 'password' => '$2y$08$7tI6IYbjB7e3n47sx/JW.eu43VzRtKwhXLredaoZVjpj2vx40Tsyi',  'idEmpleado' => 30,  'idRol' => 3],
            ['email' => 'asdfadsa',                         'name' => 'juaaan2',             'password' => '$2y$08$QRCwlCN5850Nc9StEDYlFuNwuEUwDjGnwaxy1Wv9dNqd9nWUUGNMm', 'idEmpleado' => 132, 'idRol' => 3],
            ['email' => 'ricardo.gomez@unah.edu.hn',       'name' => 'ricardo.gomez',       'password' => '$2y$08$EILHNaO5C56mIF6aX9.emuIpr5Iaz7hh5ijMQTyKLDr/LU7MPCtSy', 'idEmpleado' => 111, 'idRol' => 3],
            ['email' => 'reyna.motino@unah.edu.hn',        'name' => 'reyna.motino',        'password' => '$2y$08$V5kZ1wQZae/1H/luEil5eOo0i7uVd11nK3CvaqWbmJ/HeLGqRO/RG',  'idEmpleado' => 55,  'idRol' => 2],
            ['email' => 'cinthia.rodriguez',                'name' => 'cinthia.rodriguez',   'password' => '$2y$08$a3pOGAT28T8Syv1gUO5bo.zQysEHZW/M9VKHGhJO33jABQoN9DRY6', 'idEmpleado' => 117, 'idRol' => 3],
            ['email' => 'jenny.argueta@unah.edu.hn',       'name' => 'jenny.argueta',       'password' => '$2y$08$IZ6DekqZa3VZsI8v7tiDR.ZRUssGeEaNRohMLV3k8rCn7KuefIqY.',  'idEmpleado' => 119, 'idRol' => 3],
            ['email' => 'suhelen.contreras@unah.edu.hn',   'name' => 'suhelen.contreras',   'password' => '$2y$08$g.PK/Urn5h1MLq7s/5C.je2vpNSyk6J25UYyF359X4HYTHdgUkxr2', 'idEmpleado' => 84,  'idRol' => 3],
            ['email' => 'lccruzs@unah.edu.hn',             'name' => 'lccruzs',             'password' => '$2y$08$g2zLiCG.nf32kewb6zy9WeRKylyCUOUG.zohicHE9d/2KyZDpM4Ou',  'idEmpleado' => 131, 'idRol' => 3],
            ['email' => 'celeo.arias@unah.edu.hn',         'name' => 'celeo.arias',         'password' => '$2y$08$7PkWowL3WocGC.LvGdsaye6nRqbkKreCnby2PX6Mf2JCa6bwC3u96',  'idEmpleado' => 122, 'idRol' => 5],
            ['email' => 'josem825@gmail.com',               'name' => 'josem',               'password' => '$2y$08$uyM36rsG9BGthkPP5Iq1HeUAbZze39PWIWLI7buUoyaZDM/ynH9pm',  'idEmpleado' => 82,  'idRol' => 5],
            ['email' => '12345',                            'name' => '12345',               'password' => '$2y$08$qteyPSNAnrjqEzPbIm2VXuE/AeyNd7BJaQ23jgZjPaqMPbpOqsZU6',  'idEmpleado' => 133, 'idRol' => 6],
            ['email' => 'haydee.montalvan@unah.edu.hn',    'name' => 'haydee.montalvan',    'password' => '$2y$08$6MwC4hJSvt8q3ABKv4L97uWF7to6iZrm1QzRvMP8Zi6uWUqeuK92C',  'idEmpleado' => 125, 'idRol' => 3],
            ['email' => 'SEDI',                             'name' => 'SEDI',                'password' => '$2y$08$QqgAm/bWWzbv0LH/DGlB8.IwAgG6ryV1lJDQVSSJo34I2FgqY07zq',  'idEmpleado' => 135, 'idRol' => 1],
            ['email' => 'PLANSEDI',                         'name' => 'PLANSEDI',            'password' => '$2y$08$QqgAm/bWWzbv0LH/DGlB8.IwAgG6ryV1lJDQVSSJo34I2FgqY07zq',  'idEmpleado' => 135, 'idRol' => 1],
            ['email' => 'maguero@unah.edu.hn',             'name' => 'maguero',             'password' => '$2y$08$bF.FPv63tFTyZKOa/6HABOzXnbgd3baJNx79ypl/LvCayzOA4Dnyq',  'idEmpleado' => 136, 'idRol' => 3],
            ['email' => 'rolando.valladares@unah.edu.hn',  'name' => 'rolando.valladares',  'password' => '$2y$08$kD9IrOyIEHXyd//Kbm.3RupYpq468VI9beLoNBgHc62oDZQ7RJSt.',  'idEmpleado' => 137, 'idRol' => 3],
            ['email' => 'javier.martinez@unah.edu.hn',     'name' => 'javier.martinez',     'password' => '$2y$08$5a1XU7Ih4S0G8w41DuU3OOM85i57TasiKVcjSMINuTQGh2pFFyBJ2',  'idEmpleado' => 138, 'idRol' => 2],
            ['email' => 'harly.palencia@unah.edu.hn',      'name' => 'harly.palencia',      'password' => '$2y$08$sAA3o2Fl6y5c7mCkTzFqMeTaHlZhbJ.FNzbos6SQFnR..XR9/VTXK',  'idEmpleado' => 139, 'idRol' => 3],
            ['email' => 'ivan.aviles@unah.edu.hn',         'name' => 'ivan.aviles',         'password' => '$2y$08$Ba/8vvc5BHQuoquYq.fp6uQq37ikWFD4SlZBd36H6h2rBwnejqfqe',   'idEmpleado' => 140, 'idRol' => 3],
            ['email' => 'lithny.garcia@unah.edu.hn',       'name' => 'lithny.garcia',       'password' => '$2y$08$qV1.FXSrTArHwsSELhbv8OQbdI/bCYhZdxWSIvJJmk5q7jF4o8KFG',  'idEmpleado' => 141, 'idRol' => 3],
            ['email' => 'keydi.calix@unah.edu.hn',         'name' => 'keydi.calix',         'password' => '$2y$08$4fJXlehkNHHqZSwHl55KDu.0qEnCw.df.VEODvt.UmuottFoWvuUS',   'idEmpleado' => 99,  'idRol' => 3],
            ['email' => 'elida.solano@unah.edu.hn',        'name' => 'elida.solano',        'password' => '$2y$08$n2/Vev7Dths8I/U.3wYzDOtQWwgBcUiCuo/MhVOWs.NG.v5I7hQhy',  'idEmpleado' => 83,  'idRol' => 3],
            ['email' => 'belky.sanchez',                    'name' => 'belky.sanchez',       'password' => '$2y$08$KAKmQT5XQDG7aLw9ARpE7.i4Urk.hfxvEbHWVV9QYPoX4fgmrfd5a',  'idEmpleado' => 109, 'idRol' => 2],
            ['email' => 'wilmerrueda13@yahoo.com',          'name' => 'wilmer.rueda',        'password' => '$2y$08$Trb/Kf/4SLORGEXW4wRIDeKheKn/.7Wlfu20bDQCtQ8MNIHzYyWbS',   'idEmpleado' => 142, 'idRol' => 2],
            ['email' => 'maria.baquedano@unah.edu.hn',     'name' => 'teresa.baquedano',    'password' => '$2y$08$k8AXI37G1juwfyfN7DrFTOdGbwoMgDicVlDp0kfAgzD5g9pjBMJwC',   'idEmpleado' => 32,  'idRol' => 3],
            ['email' => 'juan',                             'name' => 'juan',                'password' => '$2y$08$pQhbrzCmOcktlttVrmWm2ucyphZqgCqjiADPVTKsI8hCZNW77pRJe',   'idEmpleado' => 133, 'idRol' => 3],
        ];

        // Insertar usuarios y asignar roles vía Spatie (model_has_roles)
        foreach ($usuarios as $u) {
            $id = DB::table('users')->insertGetId([
                'name'        => $u['name'],
                'email'       => $u['email'],
                'password'    => $u['password'],
                'idEmpleado'  => $u['idEmpleado'],
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            DB::table('model_has_roles')->insert([
                'role_id'    => $u['idRol'],
                'model_type' => 'App\\Models\\User',
                'model_id'   => $id,
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
