<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Instituciones\Institucions;
use App\Models\Departamento\Departamentos;


class DepartamentoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('departamentos')->insert([
            [
                'name' => "COORDINACION REGIONAL DE INVESTIGACION",
                'siglas' => "CR-INV",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN REGIONAL',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "COORDINACION REGIONAL DE VINCULACION UNIVERSIDAD SOCIEDAD",
                'siglas' => "CR-VUS",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN REGIONAL',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "COORDINACION REGIONAL VOAE",
                'siglas' => "CR-VOAE",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN REGIONAL',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "COORDINACION REGIONAL LO ESENCIAL",
                'siglas' => "CR-LE",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN REGIONAL',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "COORDINACION REGIONAL CULTURA DE INNOVACION EDUCATIVA",
                'siglas' => "CR-CIE",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN REGIONAL',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "COORDINACION REGIONAL DE POSGRADOS",
                'siglas' => "CR-POS",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN REGIONAL',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "ADMINISTRACION",
                'siglas' => "ADMIN",
                'estructura' => '0-00-00-00',
                'tipo' => 'ADMINISTRATIVO',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "JEFATURA REGIONAL RECURSOS HUMANOS",
                'siglas' => "JR-RRHH",
                'estructura' => '0-00-00-00',
                'tipo' => 'ADMINISTRATIVO',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "COORDINACION REGIONAL INTERNACIONALIZACION",
                'siglas' => "CR-INTER",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN REGIONAL',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "COORDINACION REGIONAL DEGT",
                'siglas' => "CR-DEGT",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN REGIONAL',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "SECCION ACADEMICA DE QUIMICA",
                'siglas' => "SA-QUI",
                'estructura' => '0-00-00-00',
                'tipo' => 'SECCIÓN ACADÉMICA',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "DEPARTAMENTO ACADEMICO DE ACUACULTURA Y BIOLOGIA MARINA",
                'siglas' => "DA-ABM",
                'estructura' => '0-00-00-00',
                'tipo' => 'DEPARTAMENTO ACADÉMICO',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "JEFATURA REGIONAL UNIDAD TECNICA DE REGISTRO",
                'siglas' => "JR-UTR",
                'estructura' => '0-00-00-00',
                'tipo' => 'ADMINISTRATIVO',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "DEPARTAMENTO ACADEMICO INGENIERIA AGROINDUSTRIAL",
                'siglas' => "DA-IA",
                'estructura' => '0-00-00-00',
                'tipo' => 'DEPARTAMENTO ACADÉMICO',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "COORDINACION REGIONAL UNIDAD DE RECURSOS DE INFORMACION",
                'siglas' => "CR-URI",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN REGIONAL',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "DEPARTAMENTO ACADEMICO COMERCIO INTERNACIONAL",
                'siglas' => "DA-CIOA",
                'estructura' => '0-00-00-00',
                'tipo' => 'DEPARTAMENTO ACADÉMICO',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "DEPARTAMENTO ACADEMICO LENGUAS EXTRANJERAS CON FUNCIONES EN HUMANIDADES Y ARTES",
                'siglas' => "DA-LEFHA",
                'estructura' => '0-00-00-00',
                'tipo' => 'DEPARTAMENTO ACADÉMICO',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "DEPARTAMENTO ACADEMICO DE BIOLOGIA CON FUNCIONES EN LOS DEPARTAMENTOS DE LA FACULTAD DE CIENCIAS",
                'siglas' => "DA-BFDFC",
                'estructura' => '0-00-00-00',
                'tipo' => 'DEPARTAMENTO ACADÉMICO',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "JEFATURA REGIONAL SERVICIOS GENERALES",
                'siglas' => "JR-SG",
                'estructura' => '0-00-00-00',
                'tipo' => 'ADMINISTRATIVO',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "COORDINACION REGIONAL DE DOCENCIA",
                'siglas' => "CR-DOC",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN REGIONAL',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "DEPARTAMENTO ACADEMICO INGENIERIA EN SISTEMAS",
                'siglas' => "DA-IS",
                'estructura' => '0-00-00-00',
                'tipo' => 'DEPARTAMENTO ACADÉMICO',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "DEPARTAMENTO ACADEMICO ADMINISTRACION DE EMPRESAS",
                'siglas' => "DA-AE",
                'estructura' => '0-00-00-00',
                'tipo' => 'DEPARTAMENTO ACADÉMICO',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "COORDINACION ACADEMICA CARRERA DE INGENIERIA EN CIENCIAS ACUICOLAS Y RECURSO MARINO COSTERO",
                'siglas' => "CA-ICA",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN ACADÉMICA',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "COORDINACION ACADEMICA COMERCIO INTERNACIONAL",
                'siglas' => "CA-CIOA",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN ACADÉMICA',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "COORDINACION ACADEMICA INGENIERÍA AGROINDUSTRIAL",
                'siglas' => "CA-IA",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN ACADÉMICA',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "DIRECCION UNAH-CURLP",
                'siglas' => "DIR-CURLP",
                'estructura' => '0-00-00-00',
                'tipo' => 'ADMINISTRATIVO',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "SECRETARIA REGIONAL",
                'siglas' => "SEC-REG",
                'estructura' => '0-00-00-00',
                'tipo' => 'ADMINISTRATIVO',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "SECCION ACADEMICA DE CIENCIAS SOCIALES",
                'siglas' => "SA-CCSS",
                'estructura' => '0-00-00-00',
                'tipo' => 'SECCIÓN ACADÉMICA',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "SECCION ACADEMICA MICROBIOLOGIA",
                'siglas' => "SA-MB",
                'estructura' => '0-00-00-00',
                'tipo' => 'SECCIÓN ACADÉMICA',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "COORDINACION ACADEMICA ADMINISTRACION DE EMPRESAS",
                'siglas' => "CA-AE",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN ACADÉMICA',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "COORDINACION ACADEMICA CARRERA DE INGENIERIA EN SISTEMAS",
                'siglas' => "CA-IS",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN ACADÉMICA',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "COORDINACION REGIONAL OBSERVATORIO DE LA VIOLENCIA",
                'siglas' => "CR-OV",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN ACADÉMICA',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "JUNTA DIRECTIVA CLAUSTRO DE DOCENTES",
                'siglas' => "JD-CD",
                'estructura' => '0-00-00-00',
                'tipo' => 'SECCIÓN ACADÉMICA',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "SISTEMA DE INFORMACION TERRITORIAL",
                'siglas' => "SIT",
                'estructura' => '0-00-00-00',
                'tipo' => 'ADMINISTRATIVO',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "TECNICO UNIVERSITARIO EN MONITOREO MARINO COSTERO",
                'siglas' => "TU-MMC",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN ACADÉMICA',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "TECNICO UNIVERSITARIO EN PRODUCCION ACUICOLA",
                'siglas' => "TU-PA",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN ACADÉMICA',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "TECNICO UNIVERSITARIO EN AGROEXPORTACION",
                'siglas' => "TU-AE",
                'estructura' => '0-00-00-00',
                'tipo' => 'COORDINACIÓN ACADÉMICA',
                'idUnidadEjecutora' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}