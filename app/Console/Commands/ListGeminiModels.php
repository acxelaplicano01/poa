<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ListGeminiModels extends Command
{
    protected $signature = 'ia:list-models {provider=gemini}';
    protected $description = 'Lista los modelos disponibles para el proveedor de IA especificado';

    public function handle()
    {
        $provider = $this->argument('provider');

        if ($provider === 'gemini') {
            $this->listGeminiModels();
        } else {
            $this->error("Proveedor '{$provider}' no soportado. Use: gemini");
        }
    }

    protected function listGeminiModels()
    {
        $apiKey = config('ia.gemini_api_key');

        if (empty($apiKey)) {
            $this->error('GEMINI_API_KEY no está configurada en el archivo .env');
            return 1;
        }

        $this->info('Consultando modelos disponibles de Gemini...');
        $this->newLine();

        try {
            $url = "https://generativelanguage.googleapis.com/v1/models";
            
            $response = Http::get($url, [
                'key' => $apiKey
            ]);

            if (!$response->successful()) {
                $this->error('Error al consultar la API de Gemini: ' . $response->body());
                return 1;
            }

            $models = $response->json('models', []);

            if (empty($models)) {
                $this->warn('No se encontraron modelos disponibles.');
                return 0;
            }

            $this->table(
                ['Nombre', 'Soporta generateContent'],
                collect($models)->map(function ($model) {
                    $name = $model['name'] ?? 'N/A';
                    $methods = $model['supportedGenerationMethods'] ?? [];
                    $supportsGenerate = in_array('generateContent', $methods) ? '✓' : '✗';
                    
                    // Extraer solo el nombre del modelo sin "models/"
                    $shortName = str_replace('models/', '', $name);
                    
                    return [$shortName, $supportsGenerate];
                })->toArray()
            );

            $this->newLine();
            $this->info('Modelos recomendados para usar en IA_PROVIDER=gemini:');
            $recommended = collect($models)
                ->filter(function ($model) {
                    $methods = $model['supportedGenerationMethods'] ?? [];
                    return in_array('generateContent', $methods);
                })
                ->pluck('name')
                ->map(fn($name) => str_replace('models/', '', $name))
                ->take(5)
                ->toArray();

            foreach ($recommended as $model) {
                $this->line("  - {$model}");
            }

            $this->newLine();
            $this->info('Para usar un modelo, configura en tu .env:');
            $this->line('GEMINI_MODEL=' . ($recommended[0] ?? 'gemini-pro'));

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
