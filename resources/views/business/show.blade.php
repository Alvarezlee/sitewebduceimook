<x-site-layout :title="$business->name" :description="$business->description">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="section-title">{{ $business->name }}</h1>
        <p class="mt-4 text-gray-600 dark:text-gray-400">{{ $business->description }}</p>

        <div class="mt-6 flex flex-wrap gap-3 text-sm">
            @if ($business->whatsapp)
                <a href="https://wa.me/{{ $business->whatsapp }}" class="btn-secondary">WhatsApp</a>
            @endif
            @if ($business->website_url)
                <a href="{{ $business->website_url }}" target="_blank" class="btn-secondary">Site web</a>
            @endif
            @if ($business->facebook_url)
                <a href="{{ $business->facebook_url }}" target="_blank" class="btn-secondary">Facebook</a>
            @endif
            @if ($business->linkedin_url)
                <a href="{{ $business->linkedin_url }}" target="_blank" class="btn-secondary">LinkedIn</a>
            @endif
        </div>

        @if ($business->services->isNotEmpty())
            <h2 class="text-xl font-bold mt-12 mb-4">Services</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach ($business->services as $service)
                    <div class="glass-card p-5">
                        <p class="font-semibold">{{ $service->name }}</p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $service->description }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($business->media->isNotEmpty())
            <h2 class="text-xl font-bold mt-12 mb-4">Galerie</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach ($business->media as $item)
                    <div class="glass-card aspect-square overflow-hidden">
                        @if ($item->type === 'video')
                            <video src="{{ Illuminate\Support\Facades\Storage::url($item->path_or_url) }}" controls class="h-full w-full object-cover"></video>
                        @else
                            <img src="{{ Illuminate\Support\Facades\Storage::url($item->path_or_url) }}" alt="{{ $item->caption }}" class="h-full w-full object-cover" loading="lazy">
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <h2 class="text-xl font-bold mt-12 mb-4">Contacter cette entreprise</h2>
        <form method="POST" action="{{ route('businesses.contact', $business) }}" class="glass-card p-6 space-y-4 max-w-lg">
            @csrf
            <div>
                <x-input-label for="name" value="Nom" />
                <x-text-input id="name" name="name" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" type="email" name="email" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="phone" value="Téléphone (optionnel)" />
                <x-text-input id="phone" name="phone" class="block mt-1 w-full" />
            </div>
            <div>
                <x-input-label for="message" value="Message" />
                <textarea id="message" name="message" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10"></textarea>
                <x-input-error :messages="$errors->get('message')" class="mt-2" />
            </div>
            <button type="submit" class="btn-primary">Envoyer</button>
        </form>
    </div>
</x-site-layout>
