<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IAService
{
    protected $provider;
    protected $apiKey;

    public function __construct()
    {
        $this->provider = config('ia.provider', 'openai'); // 'openai' o 'gemini'
        $this->apiKey = $this->getApiKey();
    }

    protected function getApiKey()
    {
        return $this->provider === 'gemini' 
            ? config('ia.gemini_api_key') 
            : config('openai.api_key');
    }

    public function generarActividad($nombreActividad, $contextoInstitucion = 'Universidad Nacional Autónoma de Honduras')
    {
        $prompt = $this->construirPrompt($nombreActividad, $contextoInstitucion);

        try {
            if ($this->provider === 'gemini') {
                return $this->generarConGemini($prompt);
            } else {
                return $this->generarConOpenAI($prompt);
            }
        } catch (\Exception $e) {
            Log::error("Error en IAService ({$this->provider}): " . $e->getMessage());
            throw $e;
        }
    }

    protected function construirPrompt($nombreActividad, $contextoInstitucion)
    {
        return "Eres un experto en planificación estratégica institucional para {$contextoInstitucion}.

                Basándote en el siguiente nombre de actividad: '{$nombreActividad}'

                Genera un JSON con los siguientes campos para una actividad del Plan Operativo Anual (POA):
                - descripcion: Una descripción detallada y profesional de la actividad (2-3 oraciones), explicando qué se hará y cómo contribuye a los objetivos institucionales
                - resultadoActividad: El resultado concreto y medible que se espera obtener de la actividad (1 oración clara)
                - poblacion_objetivo: La población específica que se beneficiará (ej: estudiantes universitarios, docentes, personal administrativo, comunidad educativa, personal de servicio, etc.)
                - medio_verificacion: Cómo se verificará el cumplimiento de la actividad (ej: informes técnicos, actas de reunión, registros fotográficos, listas de asistencia, etc.)
                - indicadores: Un array de **2-3 indicadores altamente específicos (cuantitativos, de gestión o de producto)** que reflejen directamente el cumplimiento de entregables o procesos (similar a 'Porcentaje de personal docente con carga académica verificada'). **Evita indicadores de satisfacción que requieran encuestas**. Cada indicador debe tener:
                  * nombre: Nombre conciso y muy descriptivo del indicador, siguiendo el formato de oración que detalla el entregable (máximo 100 caracteres).
                  * descripcion: Descripción clara de **qué mide el indicador y su método de cálculo**. **Incluye la fórmula solo si es un porcentaje o si el cálculo es complejo** (Ej: (Informes entregados / Informes planificados) * 100).
                  * cantidadPlanificada: **LA META PLANIFICADA. Valor numérico o porcentual exacto que se debe alcanzar para considerar cumplido el indicador** (ej: 100 si la meta es alcanzar el 100%, 5 si la meta es entregar 5 documentos)
                  * isCantidad: **true** si el indicador mide una **cantidad absoluta** (conteo de unidades), **false** en caso contrario.
                  * isPorcentaje: **true** si el indicador mide una **tasa o proporción** (porcentaje), **false** en caso contrario.

                Responde ÚNICAMENTE con el JSON válido, sin markdown ni explicaciones adicionales.";
    }

    protected function generarConOpenAI($prompt)
    {
        if (empty($this->apiKey)) {
            throw new \Exception('No se ha configurado la API Key de OpenAI. Por favor, agrega OPENAI_API_KEY en tu archivo .env o cambia el proveedor a Gemini con IA_PROVIDER=gemini');
        }

        $client = \OpenAI::client($this->apiKey);

        $response = $client->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'Eres un asistente experto en planificación estratégica institucional. Respondes únicamente con JSON válido sin formato markdown.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'max_tokens' => 600
        ]);

        $content = $response->choices[0]->message->content;
        return $this->procesarRespuesta($content);
    }

    protected function generarConGemini($prompt)
    {
        // El nombre del modelo debe ser sin "models/" ya que la URL ya lo incluye
        $modelName = config('ia.models.gemini.model', 'gemini-2.5-flash');
        
        // La URL correcta para Gemini API v1 con la API key en el query string
        $url = "https://generativelanguage.googleapis.com/v1/models/{$modelName}:generateContent?key={$this->apiKey}";

        $response = Http::timeout(30)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 1800,
                ],
            ]);

        if (!$response->successful()) {
            $errorData = $response->json();
            $error = $errorData['error']['message'] ?? $response->body();
            Log::error('Error de Gemini API', [
                'status' => $response->status(),
                'error' => $errorData,
                'url' => $url
            ]);
            throw new \Exception("Error de Gemini API: {$error}");
        }

        // Log de la respuesta completa para debugging
        $responseData = $response->json();
        Log::info('Respuesta completa de Gemini', ['response' => $responseData]);

        $content = $response->json('candidates.0.content.parts.0.text');
        
        if (!$content) {
            Log::error('No se encontró contenido en la respuesta de Gemini', [
                'response' => $responseData,
                'candidates' => $response->json('candidates')
            ]);
            throw new \Exception('No se recibió respuesta válida de Gemini. Por favor, revisa los logs para más detalles.');
        }

        return $this->procesarRespuesta($content);
    }

    protected function procesarRespuesta($content)
    {
        // Limpiar posibles markdown
        $content = trim($content);
        $content = preg_replace('/^```json\s*/', '', $content);
        $content = preg_replace('/^```\s*/', '', $content);
        $content = preg_replace('/\s*```$/', '', $content);
        
        $data = json_decode($content, true);

        if (!$data) {
            Log::error('Error decodificando JSON', ['content' => $content]);
            throw new \Exception('No se pudo procesar la respuesta de la IA. Intente nuevamente.');
        }

        // Validar que tenga los campos necesarios
        $camposRequeridos = ['descripcion', 'resultadoActividad', 'poblacion_objetivo', 'medio_verificacion'];
        foreach ($camposRequeridos as $campo) {
            if (!isset($data[$campo])) {
                $data[$campo] = '';
            }
        }

        return $data;
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function getProviderName()
    {
        return $this->provider === 'gemini' ? 'Google Gemini' : 'OpenAI';
    }
}
