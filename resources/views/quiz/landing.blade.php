<x-site-layout title="MOUNGO TIC QUIZZ" description="Le grand concours numérique du CEIMO : informatique, TIC, algorithmique, réseaux, programmation et culture numérique.">
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-50 via-white to-accent-50 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900"></div>
        <div class="relative mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-24 text-center">
            <span class="section-eyebrow">Concours national</span>
            <h1 class="mt-4 text-4xl sm:text-5xl font-extrabold">MOUNGO TIC QUIZZ</h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                Informatique · TIC · Algorithmique · Réseaux · Programmation · Culture numérique.
                Chronomètre, questions aléatoires, tentative unique, classement automatique et certificat.
            </p>

            @if ($edition)
                <div class="mt-10 glass-card p-8 max-w-xl mx-auto text-left">
                    <h2 class="font-bold text-xl">{{ $edition->name }}</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Inscription : {{ number_format((float) $edition->registration_price, 0, ',', ' ') }} XAF —
                        Durée : {{ $edition->duration_minutes }} minutes — {{ $edition->questions_per_attempt }} questions
                    </p>

                    <div class="mt-6">
                        @auth
                            @if (! $candidate)
                                <a href="{{ route('quiz.register', $edition) }}" class="btn-primary">S'inscrire au concours</a>
                            @elseif (! $candidate->isPaid())
                                <p class="text-sm text-amber-600 font-medium">Inscription en attente de paiement.</p>
                            @elseif (! $candidate->attempt)
                                <a href="{{ route('quiz.attempt.start', $edition) }}" class="btn-primary">Démarrer le quiz</a>
                            @elseif ($candidate->attempt->status === 'in_progress')
                                <a href="{{ route('quiz.attempt.show', $edition) }}" class="btn-primary">Reprendre le quiz</a>
                            @else
                                <a href="{{ route('quiz.attempt.result', $edition) }}" class="btn-primary">Voir mon résultat</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-primary">Se connecter pour s'inscrire</a>
                        @endauth

                        <a href="{{ route('quiz.leaderboard', $edition) }}" class="btn-secondary ms-3">Classement</a>
                    </div>
                </div>
            @else
                <p class="mt-10 text-gray-500">Aucune édition n'est ouverte pour le moment. Revenez bientôt !</p>
            @endif
        </div>
    </section>
</x-site-layout>
