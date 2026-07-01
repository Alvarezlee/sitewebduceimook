<x-site-layout :title="$category->name">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <span class="section-eyebrow">Bibliothèque</span>
        <h1 class="section-title mt-1 mb-10">{{ $category->name }}</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($documents as $document)
                <a href="{{ route('library.show', $document) }}" class="glass-card p-6 block hover:shadow-lg transition">
                    <p class="text-xs font-semibold text-accent-600 uppercase">{{ $document->type }}</p>
                    <h3 class="mt-2 font-bold">{{ $document->title }}</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $document->description }}</p>
                    @if ($document->is_free)
                        <span class="mt-2 inline-block text-xs font-semibold text-emerald-600">Gratuit</span>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="mt-10">{{ $documents->links() }}</div>
    </div>
</x-site-layout>
