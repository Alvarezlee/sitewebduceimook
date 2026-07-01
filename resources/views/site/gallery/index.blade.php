<x-site-layout :title="'Galerie '.($type === 'photo' ? 'Photos' : 'Vidéos')">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <span class="section-eyebrow">Galerie</span>
        <h1 class="section-title mt-1 mb-10">{{ $type === 'photo' ? 'Galerie Photos' : 'Galerie Vidéos' }}</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($albums as $album)
                <a href="{{ route('gallery.show', $album) }}" class="glass-card p-6 block hover:shadow-lg transition">
                    <h3 class="font-bold">{{ $album->title }}</h3>
                    <p class="mt-2 text-xs text-gray-500">{{ $album->items_count }} élément(s)</p>
                </a>
            @endforeach
        </div>

        <div class="mt-10">{{ $albums->links() }}</div>
    </div>
</x-site-layout>
