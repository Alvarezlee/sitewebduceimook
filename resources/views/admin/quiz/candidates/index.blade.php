<x-admin-layout title="Candidats Quiz">
    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Candidat</th><th class="p-3">Édition</th><th class="p-3">Statut inscription</th><th class="p-3">Score</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($candidates as $candidate)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $candidate->user->full_name }}</td>
                        <td class="p-3">{{ $candidate->edition->name }}</td>
                        <td class="p-3">{{ $candidate->registration_status }}</td>
                        <td class="p-3">{{ $candidate->attempt?->score ?? '—' }}</td>
                        <td class="p-3"><a href="{{ route('admin.quiz.candidates.show', $candidate) }}" class="text-brand-600 hover:underline">Détails</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $candidates->links() }}</div>
</x-admin-layout>
