<?php

namespace App\Livewire\Ai;

use App\Models\Ai\AiConversation;
use App\Services\Ai\AssistantService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

class AssistantWidget extends Component
{
    public bool $open = false;

    public string $input = '';

    public string $sessionId = '';

    public function mount(): void
    {
        $this->sessionId = session('ai_session_id') ?? (string) Str::uuid();
        session(['ai_session_id' => $this->sessionId]);
    }

    public function getMessagesProperty(): array
    {
        $conversation = AiConversation::query()
            ->where('session_id', $this->sessionId)
            ->where('channel', 'assistant')
            ->first();

        return $conversation ? $conversation->messages()->orderBy('id')->get()->all() : [];
    }

    public function send(AssistantService $assistant): void
    {
        $message = trim($this->input);

        if ($message === '') {
            return;
        }

        $this->input = '';

        $assistant->reply($this->sessionId, $message, auth()->user());
    }

    public function render(): View
    {
        return view('livewire.ai.assistant-widget');
    }
}
