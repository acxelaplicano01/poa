<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Mes\Mes;
use App\Models\Mes\Trimestre;

echo "=== VERIFICANDO TABLA MES ===\n\n";

// Verificar cuántos registros hay en mes
$countMes = DB::table('mes')->count();
echo "Total registros en tabla 'mes': " . $countMes . "\n\n";

// Verificar estructura de algunos registros
if ($countMes > 0) {
    $meses = DB::table('mes')->limit(5)->get();
    echo "Primeros registros:\n";
    foreach ($meses as $mes) {
        print_r($mes);
    }
}

echo "\n=== VERIFICANDO TABLA TRIMESTRES ===\n\n";

$trimestres = Trimestre::with('meses')->get();
foreach ($trimestres as $trimestre) {
    echo "Trimestre {$trimestre->trimestre} (ID: {$trimestre->id}): ";
    echo $trimestre->meses->count() . " meses\n";
    foreach ($trimestre->meses as $mes) {
        echo "  - Mes {$mes->mes} (ID: {$mes->id})\n";
    }
}
