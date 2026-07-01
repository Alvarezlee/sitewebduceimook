<x-site-layout title="Entreprises des membres" description="Annuaire des entreprises fondées par les membres du CEIMO.">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <span class="section-eyebrow">Écosystème</span>
        <h1 class="section-title mt-1 mb-10">Entreprises des membres</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($businesses as $business)
                <a href="{{ route('businesses.show', $business) }}" class="glass-card p-6 block hover:shadow-lg transition">
                    <h2 class="font-bold text-lg">{{ $business->name }}</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-3">{{ $business->description }}</p>
                </a>
            @endforeach
        </div>

        <div class="mt-10">{{ $businesses->links() }}</div>
    </div>
</x-site-layout>
