<?php

namespace App\Services\Ai\Providers;

use App\Services\Ai\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Fournisseur IA compatible avec l'API "Chat Completions" (OpenAI et
 * fournisseurs compatibles). L'URL de base et le modèle sont configurables
 * via config/services.php ("ai"), afin de pouvoir changer de fournisseur
 * sans modifier le code métier (AssistantService / QuizGenerationService).
 */
class OpenAiProvider implements AIProviderInterface
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl,
        private readonly string $model,
    ) {}

    public function chat(array $messages): string
    {
        $response = $this->request('/chat/completions', [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => 0.7,
        ]);

        return (string) data_get($response, 'choices.0.message.content', '');
    }

    public function generateQuizQuestions(string $subject, int $count, string $difficulty): array
    {
        $prompt = <<<PROMPT
            Génère {$count} questions à choix multiples (QCM) de niveau "{$difficulty}"
            sur le sujet "{$subject}", destinées à un concours scolaire d'informatique
            au Cameroun. Réponds STRICTEMENT en JSON, sans texte autour, sous la forme :
            {"questions": [{"question": "...", "explanation": "...", "options": [
            {"label": "...", "is_correct": true}, {"label": "...", "is_correct": false},
            {"label": "...", "is_correct": false}, {"label": "...", "is_correct": false}
            ]}]}
            Chaque question doit avoir exactement une bonne réponse parmi 4 propositions.
            PROMPT;

        $response = $this->request('/chat/completions', [
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => 'Tu es un générateur de questions pédagogiques qui répond uniquement en JSON valide.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.4,
            'response_format' => ['type' => 'json_object'],
        ]);

        $content = (string) data_get($response, 'choices.0.message.content', '{}');
        $decoded = json_decode($content, true);

        return $decoded['questions'] ?? [];
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function request(string $path, array $payload): array
    {
        if (blank($this->apiKey)) {
            throw new RuntimeException("Aucune clé API IA n'est configurée (AI_API_KEY).");
        }

        $response = Http::withToken($this->apiKey)
            ->timeout(30)
            ->baseUrl(rtrim($this->baseUrl, '/'))
            ->post(Str::start($path, '/'), $payload);

        $response->throw();

        return $response->json();
    }
}
