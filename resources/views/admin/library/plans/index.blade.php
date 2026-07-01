<x-admin-layout title="Formules d'abonnement">
    <a href="{{ route('admin.library.plans.create') }}" class="btn-primary mb-6 inline-flex">Nouvelle formule</a>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Nom</th><th class="p-3">Prix</th><th class="p-3">Durée</th><th class="p-3">Quota</th><th class="p-3">Actif</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($plans as $plan)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $plan->name }}</td>
                        <td class="p-3">{{ number_format((float) $plan->price, 0, ',', ' ') }} XAF</td>
                        <td class="p-3">{{ $plan->duration_days }} j</td>
                        <td class="p-3">{{ $plan->max_downloads ?? 'Illimité' }}</td>
                        <td class="p-3">{{ $plan->is_active ? 'Oui' : 'Non' }}</td>
                        <td class="p-3 flex gap-3">
                            <a href="{{ route('admin.library.plans.edit', $plan) }}" class="text-brand-600 hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.library.plans.destroy', $plan) }}" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $plans->links() }}</div>
</x-admin-layout>
