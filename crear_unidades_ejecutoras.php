<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\UnidadEjecutora\UnidadEjecutora;
use App\Models\Instituciones\Institucion;

echo "=== CREAR UNIDADES EJECUTORAS DE EJEMPLO ===\n\n";

// Obtener la institución UNAH
$unah = Institucion::where('nombre', 'UNAH')->first();

if (!$unah) {
    echo "Error: No se encontró la institución UNAH\n";
    exit(1);
}

echo "Institución encontrada: {$unah->nombre} (ID: {$unah->id})\n\n";

// Datos de Unidades Ejecutoras de ejemplo
$unidadesEjecutoras = [
    [
        'name' => 'CURLA',
        'descripcion' => 'Centro Universitario Regional del Litoral Atlántico - UNAH',
        'estructura' => '0-01-00-00',
    ],
    [
        'name' => 'CURNO',
        'descripcion' => 'Centro Universitario Regional del Norte - UNAH',
        'estructura' => '0-02-00-00',
    ],
    [
        'name' => 'CURSPS',
        'descripcion' => 'Centro Universitario Regional de San Pedro Sula - UNAH',
        'estructura' => '0-03-00-00',
    ],
    [
        'name' => 'CUROC',
        'descripcion' => 'Centro Universitario Regional de Occidente - UNAH',
        'estructura' => '0-04-00-00',
    ],
    [
        'name' => 'CURC',
        'descripcion' => 'Centro Universitario Regional del Centro - UNAH',
        'estructura' => '0-05-00-00',
    ],
    [
        'name' => 'UNAH-TEC DANLÍ',
        'descripcion' => 'Universidad Nacional Autónoma de Honduras - Centro Tecnológico de Danlí',
        'estructura' => '0-06-00-00',
    ],
    [
        'name' => 'UNAH-VS',
        'descripcion' => 'Universidad Nacional Autónoma de Honduras en el Valle de Sula',
        'estructura' => '0-07-00-00',
    ]
];

echo "Creando Unidades Ejecutoras de ejemplo:\n\n";

foreach ($unidadesEjecutoras as $ueData) {
    // Verificar si ya existe
    $existeUE = UnidadEjecutora::where('name', $ueData['name'])->first();
    
    if ($existeUE) {
        echo "✓ {$ueData['name']} - Ya existe (ID: {$existeUE->id})\n";
        continue;
    }
    
    // Crear nueva UE
    $nuevaUE = UnidadEjecutora::create([
        'name' => $ueData['name'],
        'descripcion' => $ueData['descripcion'],
        'estructura' => $ueData['estructura'],
        'idInstitucion' => $unah->id,
        'created_by' => 1, // Usuario admin
    ]);
    
    echo "✓ {$ueData['name']} - Creada (ID: {$nuevaUE->id})\n";
}

echo "\n=== RESUMEN ===\n";
$totalUEs = UnidadEjecutora::count();
echo "Total de Unidades Ejecutoras en la base de datos: {$totalUEs}\n";

echo "\nListado completo:\n";
$todasLasUEs = UnidadEjecutora::with('institucion')->orderBy('name')->get();
foreach ($todasLasUEs as $ue) {
    echo "- {$ue->name} ({$ue->estructura}) - {$ue->institucion->nombre}\n";
}

echo "\n=== PROCESO COMPLETADO ===\n";