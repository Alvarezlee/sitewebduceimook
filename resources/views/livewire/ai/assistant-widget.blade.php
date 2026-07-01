<div class="fixed bottom-6 right-6 z-50" x-data="{ open: @entangle('open') }">
    <button
        @click="open = !open"
        class="h-14 w-14 rounded-full bg-brand-600 text-white shadow-lg flex items-center justify-center text-2xl hover:bg-brand-700 transition"
        aria-label="Assistant CEIMO"
    >
        💬
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition
        class="absolute bottom-20 right-0 w-80 sm:w-96 glass-card p-4 shadow-2xl"
    >
        <p class="font-bold mb-3">Assistant CEIMO</p>

        <div class="h-72 overflow-y-auto space-y-3 pr-1" id="ai-messages">
            @forelse ($this->messages as $message)
                <div class="{{ $message->role === 'user' ? 'text-right' : 'text-left' }}">
                    <span class="inline-block rounded-2xl px-3 py-2 text-sm {{ $message->role === 'user' ? 'bg-brand-600 text-white' : 'bg-gray-100 dark:bg-white/10' }}">
                        {{ $message->content }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-gray-500">Bonjour ! Comment puis-je vous aider aujourd'hui ?</p>
            @endforelse
        </div>

        <form wire:submit="send" class="mt-3 flex gap-2">
            <input wire:model="input" type="text" placeholder="Écrivez votre message..." class="flex-1 rounded-full border-gray-300 dark:bg-slate-900 dark:border-white/10 text-sm">
            <button type="submit" class="btn-primary !px-4 !py-2 text-sm">Envoyer</button>
        </form>
    </div>
</div>
