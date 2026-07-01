<x-site-layout title="Événements">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <span class="section-eyebrow">Agenda</span>
        <h1 class="section-title mt-1 mb-10">Événements</h1>

        @if ($upcoming->isNotEmpty())
            <h2 class="text-xl font-bold mb-4">À venir</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach ($upcoming as $event)
                    <a href="{{ route('events.show', $event) }}" class="glass-card p-6 block hover:shadow-lg transition">
                        <p class="text-xs font-semibold text-brand-600">{{ $event->starts_at->translatedFormat('d M Y H:i') }}</p>
                        <h3 class="mt-2 font-bold text-lg">{{ $event->title }}</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $event->location }}</p>
                    </a>
                @endforeach
            </div>
        @endif

        <h2 class="text-xl font-bold mb-4">Passés</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($past as $event)
                <a href="{{ route('events.show', $event) }}" class="glass-card p-6 block hover:shadow-lg transition opacity-80">
                    <p class="text-xs font-semibold text-gray-500">{{ $event->starts_at->translatedFormat('d M Y') }}</p>
                    <h3 class="mt-2 font-bold text-lg">{{ $event->title }}</h3>
                </a>
            @endforeach
        </div>

        <div class="mt-10">{{ $past->links() }}</div>
    </div>
</x-site-layout>
