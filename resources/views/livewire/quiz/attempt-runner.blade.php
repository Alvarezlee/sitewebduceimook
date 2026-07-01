<div
    wire:poll.5s="checkExpiry"
    x-data="{
        remaining: {{ $this->remainingSeconds }},
        tick() {
            if (this.remaining > 0) { this.remaining--; }
        },
    }"
    x-init="setInterval(() => tick(), 1000)"
    @visibilitychange.window="if (document.hidden) $wire.reportTabSwitch()"
    class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-12"
>
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500">Question {{ $this->currentIndex + 1 }} / {{ count($this->questionIds) }}</p>
        <div class="glass-card px-4 py-2 font-mono text-lg font-bold text-brand-600" x-text="Math.floor(remaining/60) + ':' + String(remaining % 60).padStart(2,'0')"></div>
    </div>

    <div class="w-full h-2 bg-gray-200 dark:bg-white/10 rounded-full mb-8">
        <div class="h-2 bg-brand-600 rounded-full" style="width: {{ count($this->questionIds) > 0 ? (($this->currentIndex + 1) / count($this->questionIds)) * 100 : 0 }}%"></div>
    </div>

    @if ($this->currentQuestion)
        <div class="glass-card p-8">
            <p class="font-semibold text-lg">{{ $this->currentQuestion->question }}</p>

            @if ($this->currentQuestion->is_open_ended)
                <textarea wire:model="openAnswerText" rows="5" class="mt-6 w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10"></textarea>
            @else
                <div class="mt-6 space-y-3">
                    @foreach ($this->currentQuestion->options as $option)
                        <button
                            type="button"
                            wire:click="selectOption({{ $option->id }})"
                            class="w-full text-left rounded-xl border px-4 py-3 transition {{ $selectedOptionId === $option->id ? 'border-brand-600 bg-brand-50 dark:bg-brand-900/30' : 'border-gray-200 dark:border-white/10 hover:border-brand-300' }}"
                        >
                            {{ $option->label }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mt-8 flex items-center justify-between">
            <button wire:click="saveAndGo(-1)" @disabled($this->currentIndex === 0) class="btn-secondary disabled:opacity-40">Précédent</button>

            @if ($this->currentIndex + 1 < count($this->questionIds))
                <button wire:click="saveAndGo(1)" class="btn-primary">Suivant</button>
            @else
                <button wire:click="finish" wire:confirm="Terminer et soumettre votre tentative ?" class="btn-primary">Terminer le quiz</button>
            @endif
        </div>
    @else
        <p class="text-center text-gray-500">Aucune question disponible.</p>
    @endif
</div>
