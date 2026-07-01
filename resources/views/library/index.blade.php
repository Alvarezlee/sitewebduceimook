<x-site-layout title="Bibliothèque numérique" description="Probatoires, Baccalauréat, BEPC, CAP, BTS, concours, corrigés, fascicules et livres.">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <span class="section-eyebrow">Ressources pédagogiques</span>
        <h1 class="section-title mt-1 mb-4">Bibliothèque numérique</h1>
        <p class="max-w-2xl text-gray-600 dark:text-gray-400 mb-10">
            Accédez aux épreuves, corrigés, fascicules et livres numériques du CEIMO. Un abonnement
            vous donne accès au téléchargement sécurisé selon le quota choisi.
        </p>

        @if ($activeSubscription)
            <div class="glass-card p-4 mb-10 text-sm">
                Abonnement actif : <strong>{{ $activeSubscription->plan->name }}</strong> —
                {{ $activeSubscription->plan->hasUnlimitedDownloads() ? 'téléchargements illimités' : ($activeSubscription->plan->max_downloads - $activeSubscription->downloads_used) . ' téléchargement(s) restant(s)' }}
                — expire le {{ $activeSubscription->ends_at->translatedFormat('d/m/Y') }}.
            </div>
        @else
            <div class="glass-card p-6 mb-10 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm">Choisissez un abonnement pour débloquer les téléchargements.</p>
                <a href="{{ route('library.subscribe') }}" class="btn-primary">Voir les abonnements</a>
            </div>
        @endif

        <h2 class="text-xl font-bold mb-4">Catégories</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-12">
            @foreach ($categories as $category)
                <a href="{{ route('library.category', $category) }}" class="glass-card p-5 text-center hover:shadow-lg transition">
                    <p class="font-semibold">{{ $category->name }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $category->documents_count }} document(s)</p>
                </a>
            @endforeach
        </div>

        <h2 class="text-xl font-bold mb-4">Derniers ajouts</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($latestDocuments as $document)
                <a href="{{ route('library.show', $document) }}" class="glass-card p-5 block hover:shadow-lg transition">
                    <p class="text-xs font-semibold text-accent-600 uppercase">{{ $document->type }}</p>
                    <h3 class="mt-2 font-bold">{{ $document->title }}</h3>
                    @if ($document->is_free)
                        <span class="mt-2 inline-block text-xs font-semibold text-emerald-600">Gratuit</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</x-site-layout>
