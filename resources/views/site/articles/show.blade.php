<x-site-layout :title="$article->seo_title ?? $article->title" :description="$article->seo_description ?? $article->excerpt">
    <article class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-16">
        <p class="text-xs font-semibold text-accent-600">{{ $article->category }}</p>
        <h1 class="section-title mt-2">{{ $article->title }}</h1>
        <p class="mt-2 text-sm text-gray-500">
            Par {{ $article->author?->full_name ?? 'CEIMO' }} — {{ $article->published_at->translatedFormat('d M Y') }}
        </p>

        <div class="prose dark:prose-invert mt-8 max-w-none">
            {!! nl2br(e($article->content)) !!}
        </div>
    </article>

    @if ($related->isNotEmpty())
        <div class="bg-gray-50 dark:bg-slate-900/40 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="section-title mb-8">Autres actualités</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @foreach ($related as $item)
                        <a href="{{ route('articles.show', $item) }}" class="glass-card p-6 block hover:shadow-lg transition">
                            <h3 class="font-bold">{{ $item->title }}</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $item->excerpt }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</x-site-layout>
