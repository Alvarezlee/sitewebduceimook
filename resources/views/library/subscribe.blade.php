<x-site-layout title="Abonnement bibliothèque">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-16">
        <span class="section-eyebrow">Abonnement</span>
        <h1 class="section-title mt-1 mb-10">Choisissez votre formule</h1>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach ($plans as $plan)
                <div class="glass-card p-6 flex flex-col">
                    <h2 class="font-bold text-lg">{{ $plan->name }}</h2>
                    <p class="mt-2 text-3xl font-extrabold text-brand-600">{{ number_format((float) $plan->price, 0, ',', ' ') }} <span class="text-sm font-medium">XAF</span></p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $plan->description }}</p>
                    <ul class="mt-4 text-sm space-y-1 text-gray-600 dark:text-gray-400 flex-1">
                        <li>{{ $plan->duration_days }} jours d'accès</li>
                        <li>{{ $plan->hasUnlimitedDownloads() ? 'Téléchargements illimités' : $plan->max_downloads.' téléchargements' }}</li>
                    </ul>

                    <form method="POST" action="{{ route('library.subscribe.store', $plan) }}" class="mt-6 space-y-3">
                        @csrf
                        <input type="tel" name="phone" required placeholder="Numéro Mobile Money (6XXXXXXXX)"
                               class="w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10 text-sm">
                        <button type="submit" class="btn-primary w-full">S'abonner</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-site-layout>
