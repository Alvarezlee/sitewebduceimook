<x-site-layout title="Inscription MOUNGO TIC QUIZZ">
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 py-16">
        <span class="section-eyebrow">{{ $edition->name }}</span>
        <h1 class="section-title mt-1 mb-8">Formulaire d'inscription</h1>

        <form method="POST" action="{{ route('quiz.register.store', $edition) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="institution" value="Établissement" />
                    <x-text-input id="institution" name="institution" class="block mt-1 w-full" :value="old('institution')" required />
                    <x-input-error :messages="$errors->get('institution')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="class_level" value="Classe" />
                    <x-text-input id="class_level" name="class_level" class="block mt-1 w-full" :value="old('class_level')" required />
                    <x-input-error :messages="$errors->get('class_level')" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <x-input-label for="region" value="Région" />
                    <x-text-input id="region" name="region" class="block mt-1 w-full" :value="old('region', 'Littoral')" required />
                </div>
                <div>
                    <x-input-label for="department" value="Département" />
                    <x-text-input id="department" name="department" class="block mt-1 w-full" :value="old('department', 'Moungo')" required />
                </div>
                <div>
                    <x-input-label for="arrondissement" value="Arrondissement" />
                    <x-text-input id="arrondissement" name="arrondissement" class="block mt-1 w-full" :value="old('arrondissement')" required />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="parent_name" value="Nom du parent/tuteur" />
                    <x-text-input id="parent_name" name="parent_name" class="block mt-1 w-full" :value="old('parent_name')" required />
                </div>
                <div>
                    <x-input-label for="parent_phone" value="Téléphone du parent" />
                    <x-text-input id="parent_phone" name="parent_phone" class="block mt-1 w-full" :value="old('parent_phone')" required />
                </div>
            </div>

            <div>
                <x-input-label for="photo" value="Photo (optionnelle)" />
                <input id="photo" name="photo" type="file" accept="image/*" class="block mt-1 w-full text-sm">
                <x-input-error :messages="$errors->get('photo')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="phone" value="Numéro Mobile Money (pour le paiement)" />
                <x-text-input id="phone" name="phone" class="block mt-1 w-full" placeholder="6XXXXXXXX" required />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <button type="submit" class="btn-primary w-full">
                Payer {{ number_format((float) $edition->registration_price, 0, ',', ' ') }} XAF et s'inscrire
            </button>
        </form>
    </div>
</x-site-layout>
