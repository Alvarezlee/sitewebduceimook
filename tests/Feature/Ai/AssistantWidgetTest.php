<?php

use App\Livewire\Ai\AssistantWidget;
use App\Services\Ai\Contracts\AIProviderInterface;
use Livewire\Livewire;
use Tests\Fakes\FakeAIProvider;

test('the assistant widget sends a message and displays the reply', function () {
    app()->instance(AIProviderInterface::class, new FakeAIProvider);

    $component = Livewire::test(AssistantWidget::class);

    $component->set('input', 'Bonjour')->call('send');

    $component->assertSee('assistant CEIMO');
});
