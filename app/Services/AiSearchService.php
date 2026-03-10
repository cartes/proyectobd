<?php

namespace App\Services;

use App\Models\ProfileDetail;
use OpenAI\Laravel\Facades\OpenAI;

class AiSearchService
{
    /**
     * Parse a natural language query into structured discovery filters.
     *
     * @return array{city: string|null, age_min: int|null, age_max: int|null, interests: array}
     */
    public function parseQuery(string $query): array
    {
        $interests = ProfileDetail::interestsOptions();
        $interestKeys = implode(', ', array_keys($interests));

        $systemPrompt = <<<PROMPT
Eres un asistente de búsqueda para una plataforma de citas. 
Extrae los filtros de búsqueda de la consulta del usuario y devuelve ÚNICAMENTE un objeto JSON válido con esta estructura:
{
  "city": "nombre de ciudad o null",
  "age_min": número entero o null,
  "age_max": número entero o null,
  "interests": ["array de claves de intereses o array vacío"]
}

Las claves de intereses válidas son: $interestKeys

Reglas:
- Si el usuario menciona una edad exacta (ej. "25 años"), usa age_min y age_max con el mismo valor ±2.
- Si dice "joven", interpreta como age_min: 18, age_max: 30.
- Si dice "madura", interpreta como age_min: 35.
- Normaliza nombres de ciudad a formato título (ej. "buenos aires" → "Buenos Aires").
- Si no se menciona un filtro, usa null o array vacío según corresponda.
- Devuelve SOLO el JSON, sin explicaciones ni markdown.
PROMPT;

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $query],
                ],
                'max_tokens' => 200,
                'temperature' => 0.1,
            ]);

            $content = $response->choices[0]->message->content ?? '{}';
            $filters = json_decode($content, true);

            if (! is_array($filters)) {
                return $this->emptyFilters();
            }

            return [
                'city' => isset($filters['city']) && $filters['city'] ? (string) $filters['city'] : null,
                'age_min' => isset($filters['age_min']) && is_numeric($filters['age_min']) ? (int) $filters['age_min'] : null,
                'age_max' => isset($filters['age_max']) && is_numeric($filters['age_max']) ? (int) $filters['age_max'] : null,
                'interests' => isset($filters['interests']) && is_array($filters['interests']) ? $filters['interests'] : [],
            ];
        } catch (\Throwable $e) {
            return $this->emptyFilters();
        }
    }

    private function emptyFilters(): array
    {
        return ['city' => null, 'age_min' => null, 'age_max' => null, 'interests' => []];
    }
}
