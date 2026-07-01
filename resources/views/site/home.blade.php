<x-site-layout>
    <!-- Hero -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-50 via-white to-accent-50 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24 sm:py-32">
            <div class="max-w-3xl">
                <span class="section-eyebrow">Cercle des Enseignants d'Informatique du Moungo</span>
                <h1 class="mt-4 text-4xl sm:text-6xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Former, connecter et valoriser le <span class="text-brand-600 dark:text-brand-400">numérique</span> dans le Moungo.
                </h1>
                <p class="mt-6 text-lg text-gray-600 dark:text-gray-300">
                    Bibliothèque numérique, concours MOUNGO TIC QUIZZ, boutique de ressources pédagogiques et
                    annuaire des entreprises membres : la plateforme officielle du CEIMO.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('quiz.landing') }}" class="btn-primary">Participer au MOUNGO TIC QUIZZ</a>
                    <a href="{{ route('library.index') }}" class="btn-secondary">Explorer la bibliothèque</a>
                </div>
            </div>
        </div>
    </section>

    @if ($articles->isNotEmpty())
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="section-eyebrow">Actualités</span>
                <h2 class="section-title mt-1">Dernières nouvelles du CEIMO</h2>
            </div>
            <a href="{{ route('articles.index') }}" class="text-sm font-semibold text-brand-600 hover:underline">Voir tout →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($articles as $article)
                <a href="{{ route('articles.show', $article) }}" class="glass-card p-6 block hover:shadow-lg transition">
                    <p class="text-xs font-semibold text-accent-600">{{ $article->category }}</p>
                    <h3 class="mt-2 font-bold text-lg">{{ $article->title }}</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-3">{{ $article->excerpt }}</p>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    @if ($events->isNotEmpty())
    <section class="bg-gray-50 dark:bg-slate-900/40 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <span class="section-eyebrow">Agenda</span>
                    <h2 class="section-title mt-1">Événements à venir</h2>
                </div>
                <a href="{{ route('events.index') }}" class="text-sm font-semibold text-brand-600 hover:underline">Voir tout →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach ($events as $event)
                    <a href="{{ route('events.show', $event) }}" class="glass-card p-6 block hover:shadow-lg transition">
                        <p class="text-xs font-semibold text-brand-600">{{ $event->starts_at->translatedFormat('d M Y') }}</p>
                        <h3 class="mt-2 font-bold text-lg">{{ $event->title }}</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $event->location }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($businesses->isNotEmpty())
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="section-eyebrow">Écosystème</span>
                <h2 class="section-title mt-1">Entreprises des membres</h2>
            </div>
            <a href="{{ route('businesses.index') }}" class="text-sm font-semibold text-brand-600 hover:underline">Voir tout →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach ($businesses as $business)
                <a href="{{ route('businesses.show', $business) }}" class="glass-card p-4 flex items-center justify-center h-24 text-center text-sm font-semibold hover:shadow-lg transition">
                    {{ $business->name }}
                </a>
            @endforeach
        </div>
    </section>
    @endif

    @if ($reviews->isNotEmpty())
    <section class="bg-gray-50 dark:bg-slate-900/40 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <span class="section-eyebrow">Témoignages</span>
            <h2 class="section-title mt-1 mb-8">Ce qu'ils en disent</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($reviews as $review)
                    <div class="glass-card p-6">
                        <div class="text-amber-500 text-sm">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
                        <p class="mt-3 text-sm text-gray-700 dark:text-gray-300">{{ $review->content }}</p>
                        <p class="mt-4 font-semibold text-sm">{{ $review->name }}</p>
                        <p class="text-xs text-gray-500">{{ $review->role_title }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</x-site-layout>
