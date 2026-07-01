<?php

use App\Models\Ai\AiConversation;
use App\Services\Ai\AssistantService;
use App\Services\Ai\Contracts\AIProviderInterface;
use Tests\Fakes\FakeAIProvider;

test('the assistant service persists the conversation and replies via the ai provider', function () {
    $fake = new FakeAIProvider;
    app()->instance(AIProviderInterface::class, $fake);

    $service = app(AssistantService::class);

    $reply = $service->reply('session-123', 'Bonjour, comment m\'inscrire au quiz ?');

    expect($reply->role)->toBe('assistant')
        ->and($reply->content)->toContain('assistant CEIMO');

    $conversation = AiConversation::query()->where('session_id', 'session-123')->first();
    expect($conversation)->not->toBeNull()
        ->and($conversation->messages)->toHaveCount(2);

    expect($fake->lastMessages[0]['role'])->toBe('system');
});
