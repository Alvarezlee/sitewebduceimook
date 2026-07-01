<x-site-layout title="Résultat MOUNGO TIC QUIZZ">
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 py-16 text-center">
        <span class="section-eyebrow">{{ $edition->name }}</span>
        <h1 class="section-title mt-1 mb-8">Votre résultat</h1>

        @php($attempt = $candidate->attempt)

        @if ($attempt->status === 'in_progress')
            <p class="text-gray-500">Votre tentative est toujours en cours.</p>
            <a href="{{ route('quiz.attempt.show', $edition) }}" class="btn-primary mt-6 inline-flex">Continuer</a>
        @else
            <div class="glass-card p-10">
                <p class="text-5xl font-extrabold text-brand-600">{{ $attempt->score }}<span class="text-lg">/100</span></p>
                @if ($attempt->rank)
                    <p class="mt-4 text-lg font-semibold">Classement : {{ $attempt->rank }}{{ $attempt->rank === 1 ? 'er' : 'e' }}</p>
                @endif

                @if ($attempt->status === 'submitted')
                    <p class="mt-4 text-sm text-amber-600">Certaines réponses sont en cours de correction manuelle.</p>
                @endif

                @if ($attempt->certificate)
                    <a href="{{ URL::temporarySignedRoute('quiz.certificate.download', now()->addMinutes(10), ['certificate' => $attempt->certificate->id]) }}" class="btn-primary mt-6 inline-flex">
                        Télécharger mon certificat
                    </a>
                @endif
            </div>

            <a href="{{ route('quiz.leaderboard', $edition) }}" class="mt-8 inline-block text-sm font-semibold text-brand-600 hover:underline">Voir le classement complet →</a>
        @endif
    </div>
</x-site-layout>
