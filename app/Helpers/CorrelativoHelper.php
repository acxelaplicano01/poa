<?php

namespace App\Helpers;

class CorrelativoHelper
{
    /**
     * Genera el correlativo para una requisición según el tipo y nombre del departamento.
     *
     * @param string $tipo Tipo de departamento (ej: 'COORDINACION REGIONAL', 'DEPARTAMENTO ACADEMICO', 'COORDINACION ACADEMICA', 'SECCION ACADEMICA', etc)
     * @param string $nombre Nombre completo del departamento
     * @param int $anio Año de la requisición
     * @param int $numero Número correlativo
     * @return string
     */
    public static function generarCorrelativo($tipo, $nombre, $anio, $numero)
    {
        // Mapa de nombres exactos a siglas (basado en tu tabla)
        $siglas = [
            'COORDINACION REGIONAL DE INVESTIGACION' => 'CR-INV',
            'COORDINACION REGIONAL DE VINCULACION UNIVERSIDAD SOCIEDAD' => 'CR-VUS',
            'COORDINACION REGIONAL VOAE' => 'CR-VOAE',
            'COORDINACION REGIONAL LO ESENCIAL' => 'CR-LE',
            'COORDINACION REGIONAL CULTURA DE INNOVACION EDUCATIVA' => 'CR-CIE',
            'COORDINACION REGIONAL DE POSGRADOS' => 'CR-POS',
            'ADMINISTRACION' => 'ADMIN',
            'JEFATURA REGIONAL RECURSOS HUMANOS' => 'JR-RRHH',
            'COORDINACION REGIONAL INTERNACIONALIZACION' => 'CR-INTER',
            'COORDINACION REGIONAL DEGT' => 'CR-DEGT',
            'SECCION ACADEMICA DE QUIMICA' => 'SA-QUI',
            'DEPARTAMENTO ACADEMICO DE ACUACULTURA Y BIOLOGIA MARINA' => 'DA-ABM',
            'JEFATURA REGIONAL UNIDAD TECNICA DE REGISTRO' => 'JR-UTR',
            'DEPARTAMENTO ACADEMICO INGENIERIA AGROINDUSTRIAL' => 'DA-IA',
            'COORDINACION REGIONAL UNIDAD DE RECURSOS DE INFORMACION' => 'CR-URI',
            'DEPARTAMENTO ACADEMICO COMERCIO INTERNACIONAL' => 'DA-CIOA',
            'DEPARTAMENTO ACADEMICO LENGUAS EXTRANJERAS CON FUNCIONES EN HUMANIDADES Y ARTES' => 'DA-LEFHA',
            'DEPARTAMENTO ACADEMICO DE BIOLOGIA CON FUNCIONES EN LOS DEPARTAMENTOS DE LA FACULTAD DE CIENCIAS' => 'DA-BFDFC',
            'JEFATURA REGIONAL SERVICIOS GENERALES' => 'JR-SG',
            'COORDINACION REGIONAL DE DOCENCIA' => 'CR-DOC',
            'DEPARTAMENTO ACADEMICO INGENIERIA EN SISTEMAS' => 'DA-IS',
            'DEPARTAMENTO ACADEMICO ADMINISTRACION DE EMPRESAS' => 'DA-AE',
            'COORDINACION ACADEMICA CARRERA DE INGENIERIA EN CIENCIAS ACUICOLAS Y RECURSO MARINO COSTERO' => 'CA-ICA',
            'COORDINACION ACADEMICA COMERCIO INTERNACIONAL' => 'CA-CIOA',
            'COORDINACION ACADEMICA INGENIERÍA AGROINDUSTRIAL' => 'CA-IA',
            'DIRECCION UNAH-CURLP' => 'DIR-CURLP',
            'SECRETARIA REGIONAL' => 'SEC-REG',
            'SECCION ACADEMICA DE CIENCIAS SOCIALES' => 'SA-CCSS',
            'SECCION ACADEMICA MICROBIOLOGIA' => 'SA-MB',
            'COORDINACION ACADEMICA ADMINISTRACION DE EMPRESAS' => 'CA-AE',
            'COORDINACION ACADEMICA CARRERA DE INGENIERIA EN SISTEMAS' => 'CA-IS',
            'COORDINACION REGIONAL OBSERVATORIO DE LA VIOLENCIA' => 'CR-OV',
            'JUNTA DIRECTIVA CLAUSTRO DE DOCENTES' => 'JD-CD',
            'SISTEMA DE INFORMACION TERRITORIAL' => 'SIT',
            'TECNICO UNIVERSITARIO EN MONITOREO MARINO COSTERO' => 'TU-MMC',
            'TECNICO UNIVERSITARIO EN PRODUCCION ACUICOLA' => 'TU-PA',
            'TECNICO UNIVERSITARIO EN AGROEXPORTACION' => 'TU-AE',
            'SECRETARIA EJECUTIVA DE DESARROLLO INSITITUCIONAL' => 'SEDI',
            'COMUNICACION ESTRATEGICA UNAH-CURLP' => 'CE-UNAH_CURLP',
            'PRUEBA' => 'PRUEBA',
        ];
        $nombre = strtoupper(trim($nombre));
        $sigla = $siglas[$nombre] ?? null;
        if ($sigla) {
            return $sigla . '-' . $anio . '-' . $numero;
        }
        // Si no está en el mapa, usar el método anterior:
        $prefijos = [
            'COORDINACION REGIONAL' => 'CR',
            'DEPARTAMENTO ACADEMICO' => 'DA',
            'COORDINACION ACADEMICA' => 'CA',
            'SECCION ACADEMICA' => 'SA',
        ];
        $tipo = strtoupper($tipo);
        $prefijo = $prefijos[$tipo] ?? 'OT';
        $iniciales = self::obtenerIniciales($nombre);
        return $prefijo . '-' . $iniciales . '-' . $anio . '-' . $numero;
    }

    /**
     * Obtiene las iniciales del nombre del departamento.
     * Ejemplo: "COORDINACION REGIONAL DE INVESTIGACION" => "INV"
     *          "DEPARTAMENTO ACADEMICO DE ACUACULTURA Y BIOLOGIA MARINA" => "ABM"
     *          "SECCION ACADEMICA DE QUIMICA" => "QUI"
     *          "COORDINACION REGIONAL VOAE" => "VOAE"
     *
     * @param string $nombre
     * @return string
     */
    public static function obtenerIniciales($nombre)
    {
        $nombre = strtoupper($nombre);
        // Casos especiales conocidos
        $especiales = [
            'VOAE' => 'VOAE',
            'ACUACULTURA Y BIOLOGIA MARINA' => 'ABM',
            'QUIMICA' => 'QUI',
            'INVESTIGACION' => 'INV',
        ];
        foreach ($especiales as $clave => $sigla) {
            if (strpos($nombre, $clave) !== false) {
                return $sigla;
            }
        }
        // Si no es especial, tomar la primera letra de cada palabra relevante (no "DE", "Y", "LA", etc)
        $excluir = ['DE', 'LA', 'Y', 'DEL', 'EL', 'LOS', 'LAS', 'EN', 'A'];
        $palabras = explode(' ', $nombre);
        $iniciales = '';
        foreach ($palabras as $palabra) {
            if (!in_array($palabra, $excluir) && strlen($palabra) > 2) {
                $iniciales .= mb_substr($palabra, 0, 1);
            }
        }
        return $iniciales ?: 'GEN';
    }
}
