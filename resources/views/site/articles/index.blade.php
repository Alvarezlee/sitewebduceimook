<x-site-layout title="Actualités">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <span class="section-eyebrow">Actualités</span>
        <h1 class="section-title mt-1 mb-10">Toutes les actualités du CEIMO</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($articles as $article)
                <a href="{{ route('articles.show', $article) }}" class="glass-card p-6 block hover:shadow-lg transition">
                    <p class="text-xs font-semibold text-accent-600">{{ $article->category }}</p>
                    <h2 class="mt-2 font-bold text-lg">{{ $article->title }}</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-3">{{ $article->excerpt }}</p>
                    <p class="mt-3 text-xs text-gray-500">{{ $article->published_at->translatedFormat('d M Y') }}</p>
                </a>
            @endforeach
        </div>

        <div class="mt-10">{{ $articles->links() }}</div>
    </div>
</x-site-layout>
