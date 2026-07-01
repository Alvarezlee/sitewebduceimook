<x-site-layout :title="$document->title" :description="$document->description">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-16">
        <p class="text-xs font-semibold text-accent-600 uppercase">{{ $document->type }} — {{ $document->category->name }}</p>
        <h1 class="section-title mt-2">{{ $document->title }}</h1>
        <p class="mt-4 text-gray-600 dark:text-gray-400">{{ $document->description }}</p>

        <div class="mt-8 glass-card p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <p>Année : {{ $document->year ?? '—' }}</p>
                <p>Téléchargements : {{ $document->downloads_count }}</p>
            </div>

            @auth
                @if ($canDownload)
                    <a href="{{ $downloadUrl }}" class="btn-primary">Télécharger le document</a>
                @else
                    <a href="{{ route('library.subscribe') }}" class="btn-primary">S'abonner pour télécharger</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-primary">Se connecter pour télécharger</a>
            @endauth
        </div>
    </div>
</x-site-layout>
