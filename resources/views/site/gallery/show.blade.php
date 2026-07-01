<x-site-layout :title="$album->title">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="section-title mb-10">{{ $album->title }}</h1>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($album->items as $item)
                <div class="glass-card aspect-square flex items-center justify-center overflow-hidden">
                    @if ($album->type === 'photo')
                        <img src="{{ Illuminate\Support\Facades\Storage::url($item->path_or_url) }}" alt="{{ $item->caption }}" class="h-full w-full object-cover" loading="lazy">
                    @else
                        <video src="{{ Illuminate\Support\Facades\Storage::url($item->path_or_url) }}" controls class="h-full w-full object-cover"></video>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-site-layout>
