<x-admin-layout :title="$product->exists ? 'Modifier le produit' : 'Nouveau produit'">
    <form method="POST" action="{{ $product->exists ? route('admin.shop.products.update', $product) : route('admin.shop.products.store') }}" enctype="multipart/form-data" class="glass-card p-6 max-w-xl space-y-4">
        @csrf
        @if ($product->exists) @method('PUT') @endif

        <div>
            <x-input-label for="name" value="Nom" />
            <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $product->name)" required />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="product_category_id" value="Catégorie" />
                <select id="product_category_id" name="product_category_id" class="mt-1 w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10 text-sm">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('product_category_id', $product->product_category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="type" value="Type" />
                <select id="type" name="type" class="mt-1 w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10 text-sm">
                    @foreach (['livre','fascicule','cours','pdf','logiciel','formation','abonnement'] as $type)
                        <option value="{{ $type }}" @selected(old('type', $product->type) === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <x-input-label for="description" value="Description" />
            <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="price" value="Prix (XAF)" />
                <x-text-input id="price" name="price" type="number" class="block mt-1 w-full" :value="old('price', $product->price)" required />
            </div>
            <div>
                <x-input-label for="stock" value="Stock (vide = illimité)" />
                <x-text-input id="stock" name="stock" type="number" class="block mt-1 w-full" :value="old('stock', $product->stock)" />
            </div>
        </div>

        <div>
            <x-input-label for="file" value="Fichier numérique" />
            <input id="file" name="file" type="file" class="block mt-1 w-full text-sm">
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_digital" value="1" @checked(old('is_digital', $product->is_digital ?? true))>
            Produit numérique
        </label>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $product->is_published ?? true))>
            Publié
        </label>

        <x-primary-button>Enregistrer</x-primary-button>
    </form>
</x-admin-layout>
