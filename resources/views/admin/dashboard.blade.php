<x-admin-layout title="Tableau de bord">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ([
            ['label' => 'Utilisateurs', 'value' => $stats['users']],
            ['label' => 'Abonnements bibliothèque actifs', 'value' => $stats['active_library_subscriptions']],
            ['label' => 'Candidats quiz', 'value' => $stats['quiz_candidates']],
            ['label' => 'Commandes', 'value' => $stats['orders']],
            ['label' => 'Entreprises en attente', 'value' => $stats['businesses_pending']],
            ['label' => 'Revenus totaux (XAF)', 'value' => number_format((float) $stats['revenue_total'], 0, ',', ' ')],
        ] as $card)
            <div class="rounded-2xl border border-gray-200/70 dark:border-white/10 bg-white/80 dark:bg-slate-900/60 backdrop-blur-xl p-6 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $card['label'] }}</p>
                <p class="mt-2 text-3xl font-bold text-blue-700 dark:text-blue-400">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>
</x-admin-layout>
