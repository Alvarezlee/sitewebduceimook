<x-site-layout title="Classement MOUNGO TIC QUIZZ">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-16">
        <span class="section-eyebrow">{{ $edition->name }}</span>
        <h1 class="section-title mt-1 mb-10">Classement</h1>

        @if ($ranking->getCollection()->take(3)->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
                @foreach ($ranking->getCollection()->take(3) as $entry)
                    <div class="glass-card p-6 text-center">
                        <p class="text-3xl">{{ ['🥇','🥈','🥉'][$entry->rank - 1] ?? '' }}</p>
                        <p class="mt-2 font-bold">{{ $entry->candidate->user->full_name }}</p>
                        <p class="text-sm text-gray-500">{{ $entry->score }} / 100</p>
                    </div>
                @endforeach
            </div>
        @endif

        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-white/10">
                    <th class="py-2">Rang</th>
                    <th class="py-2">Candidat</th>
                    <th class="py-2">Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ranking as $entry)
                    <tr class="border-b border-gray-100 dark:border-white/5">
                        <td class="py-2">{{ $entry->rank }}</td>
                        <td class="py-2">{{ $entry->candidate->user->full_name }}</td>
                        <td class="py-2">{{ $entry->score }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-8">{{ $ranking->links() }}</div>
    </div>
</x-site-layout>
