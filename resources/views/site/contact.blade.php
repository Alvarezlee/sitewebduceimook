<x-site-layout title="Contact">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-16 grid grid-cols-1 lg:grid-cols-2 gap-12">
        <div>
            <span class="section-eyebrow">Nous contacter</span>
            <h1 class="section-title mt-1 mb-6">Parlons-en</h1>

            <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="name" value="Nom complet" />
                    <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" type="email" name="email" class="block mt-1 w-full" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="phone" value="Téléphone (optionnel)" />
                    <x-text-input id="phone" name="phone" class="block mt-1 w-full" :value="old('phone')" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="subject" value="Sujet" />
                    <x-text-input id="subject" name="subject" class="block mt-1 w-full" :value="old('subject')" required />
                    <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="message" value="Message" />
                    <textarea id="message" name="message" rows="5" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10 shadow-sm focus:border-brand-500 focus:ring-brand-500">{{ old('message') }}</textarea>
                    <x-input-error :messages="$errors->get('message')" class="mt-2" />
                </div>

                <x-recaptcha />

                <button type="submit" class="btn-primary">Envoyer le message</button>
            </form>
        </div>

        <div class="space-y-6">
            <div class="glass-card p-6">
                <h2 class="font-bold mb-2">Coordonnées</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Email : contact@ceimo.cm</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Téléphone : +237 6XX XXX XXX</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">WhatsApp : +237 6XX XXX XXX</p>
            </div>
            <div class="glass-card overflow-hidden">
                <iframe
                    title="Localisation CEIMO"
                    class="w-full h-64"
                    loading="lazy"
                    src="https://www.google.com/maps?q=Nkongsamba,Cameroun&output=embed">
                </iframe>
            </div>
        </div>
    </div>
</x-site-layout>
