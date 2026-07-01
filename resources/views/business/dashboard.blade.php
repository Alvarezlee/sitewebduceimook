<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Ma vitrine entreprise</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if ($business && ! $business->is_published)
                <div class="rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200 px-4 py-3 text-sm">
                    Votre vitrine est en attente de validation par un administrateur.
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <form method="POST" action="{{ $business ? route('business.dashboard.update') : route('business.dashboard.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @if ($business)
                        @method('PUT')
                    @endif

                    <div>
                        <x-input-label for="name" value="Nom de l'entreprise" />
                        <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $business?->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Présentation" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700">{{ old('description', $business?->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="whatsapp" value="WhatsApp" />
                            <x-text-input id="whatsapp" name="whatsapp" class="block mt-1 w-full" :value="old('whatsapp', $business?->whatsapp)" />
                        </div>
                        <div>
                            <x-input-label for="website_url" value="Site web" />
                            <x-text-input id="website_url" name="website_url" class="block mt-1 w-full" :value="old('website_url', $business?->website_url)" />
                        </div>
                        <div>
                            <x-input-label for="facebook_url" value="Facebook" />
                            <x-text-input id="facebook_url" name="facebook_url" class="block mt-1 w-full" :value="old('facebook_url', $business?->facebook_url)" />
                        </div>
                        <div>
                            <x-input-label for="linkedin_url" value="LinkedIn" />
                            <x-text-input id="linkedin_url" name="linkedin_url" class="block mt-1 w-full" :value="old('linkedin_url', $business?->linkedin_url)" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="address" value="Adresse" />
                        <x-text-input id="address" name="address" class="block mt-1 w-full" :value="old('address', $business?->address)" />
                    </div>

                    <div>
                        <x-input-label for="logo" value="Logo" />
                        <input id="logo" name="logo" type="file" accept="image/*" class="block mt-1 w-full text-sm">
                    </div>

                    <x-primary-button>Enregistrer</x-primary-button>
                </form>
            </div>

            @if ($business)
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3 class="font-semibold mb-4">Services</h3>
                    @foreach ($business->services as $service)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <p>{{ $service->name }}</p>
                            <form method="POST" action="{{ route('business.dashboard.services.destroy', $service) }}">
                                @csrf @method('DELETE')
                                <button class="text-red-600 text-sm hover:underline">Supprimer</button>
                            </form>
                        </div>
                    @endforeach

                    <form method="POST" action="{{ route('business.dashboard.services.store') }}" class="mt-4 flex gap-3">
                        @csrf
                        <input name="name" placeholder="Nom du service" required class="flex-1 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 text-sm">
                        <button class="btn-primary text-sm">Ajouter</button>
                    </form>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3 class="font-semibold mb-4">Photos / vidéos</h3>
                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-3 mb-4">
                        @foreach ($business->media as $item)
                            <form method="POST" action="{{ route('business.dashboard.medias.destroy', $item) }}" class="relative group">
                                @csrf @method('DELETE')
                                <div class="aspect-square rounded-lg bg-gray-100 dark:bg-gray-900"></div>
                                <button class="absolute inset-0 bg-black/50 text-white text-xs opacity-0 group-hover:opacity-100">Supprimer</button>
                            </form>
                        @endforeach
                    </div>

                    <form method="POST" action="{{ route('business.dashboard.medias.store') }}" enctype="multipart/form-data" class="flex flex-wrap gap-3 items-center">
                        @csrf
                        <select name="type" class="rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 text-sm">
                            <option value="photo">Photo</option>
                            <option value="video">Vidéo</option>
                            <option value="portfolio">Portfolio</option>
                        </select>
                        <input type="file" name="file" required class="text-sm">
                        <button class="btn-primary text-sm">Ajouter</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
