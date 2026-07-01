<?php

namespace App\Services\Ai;

use App\Models\Ai\AiConversation;
use App\Models\Ai\AiMessage;
use App\Models\User;
use App\Services\Ai\Contracts\AIProviderInterface;

class AssistantService
{
    private const SYSTEM_PROMPT = <<<'PROMPT'
        Tu es l'assistant virtuel officiel du CEIMO (Cercle des Enseignants
        d'Informatique du Moungo). Tu réponds en français, de façon brève,
        chaleureuse et professionnelle. Tu aides les visiteurs à :
        - comprendre la bibliothèque numérique et les abonnements,
        - s'inscrire au concours MOUNGO TIC QUIZZ,
        - trouver des produits dans la boutique,
        - découvrir les entreprises membres,
        - être orientés vers le formulaire de contact pour toute question
          nécessitant une intervention humaine.
        Ne donne jamais d'informations inventées sur les prix ou les dates :
        invite l'utilisateur à consulter la page correspondante si tu n'es
        pas certain.
        PROMPT;

    public function __construct(private readonly AIProviderInterface $provider) {}

    public function reply(string $sessionId, string $message, ?User $user = null): AiMessage
    {
        $conversation = AiConversation::query()->firstOrCreate(
            [
                'session_id' => $sessionId,
                'channel' => 'assistant',
            ],
            ['user_id' => $user?->id],
        );

        $conversation->messages()->create(['role' => 'user', 'content' => $message]);

        $history = $conversation->messages()
            ->latest('id')
            ->take(10)
            ->get()
            ->reverse()
            ->map(fn (AiMessage $m) => ['role' => $m->role, 'content' => $m->content])
            ->values()
            ->all();

        $payload = [
            ['role' => 'system', 'content' => self::SYSTEM_PROMPT],
            ...$history,
        ];

        $reply = $this->provider->chat($payload);

        return $conversation->messages()->create(['role' => 'assistant', 'content' => $reply]);
    }
}
