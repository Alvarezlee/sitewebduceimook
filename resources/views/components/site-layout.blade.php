@props(['title' => null, 'description' => null])
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth" x-data="{ dark: localStorage.getItem('ceimo-theme') === 'dark', mobileNav: false }" x-init="$watch('dark', v => { localStorage.setItem('ceimo-theme', v ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', v) }); document.documentElement.classList.toggle('dark', dark)">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? $title.' — '.config('app.name') : config('app.name').' — Cercle des Enseignants d\'Informatique du Moungo' }}</title>
        <meta name="description" content="{{ $description ?? "Plateforme officielle du CEIMO : formation, bibliothèque numérique, MOUNGO TIC QUIZZ, boutique et entreprises membres." }}">

        <meta property="og:title" content="{{ $title ?? config('app.name') }}">
        <meta property="og:description" content="{{ $description ?? "Plateforme officielle du CEIMO." }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="antialiased font-sans">
        <header class="sticky top-0 z-50 border-b border-gray-200/70 dark:border-white/10 bg-white/80 dark:bg-slate-950/70 backdrop-blur-xl">
            <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-xl tracking-tight">
                    <span class="text-brand-700 dark:text-brand-400">CEIMO</span>
                </a>

                <div class="hidden lg:flex items-center gap-6 text-sm font-medium">
                    <div class="relative group">
                        <button class="flex items-center gap-1 hover:text-brand-600 dark:hover:text-brand-400">À propos</button>
                        <div class="absolute left-0 top-full hidden group-hover:block pt-2 w-56">
                            <div class="glass-card p-2">
                                <a href="{{ route('pages.show', 'historique') }}" class="block rounded-lg px-3 py-2 hover:bg-brand-50 dark:hover:bg-white/5">Historique</a>
                                <a href="{{ route('pages.show', 'vision') }}" class="block rounded-lg px-3 py-2 hover:bg-brand-50 dark:hover:bg-white/5">Vision</a>
                                <a href="{{ route('pages.show', 'mission') }}" class="block rounded-lg px-3 py-2 hover:bg-brand-50 dark:hover:bg-white/5">Mission</a>
                                <a href="{{ route('team.bureau') }}" class="block rounded-lg px-3 py-2 hover:bg-brand-50 dark:hover:bg-white/5">Bureau Exécutif</a>
                                <a href="{{ route('team.members') }}" class="block rounded-lg px-3 py-2 hover:bg-brand-50 dark:hover:bg-white/5">Membres</a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('articles.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400">Actualités</a>
                    <a href="{{ route('events.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400">Événements</a>

                    <div class="relative group">
                        <button class="flex items-center gap-1 hover:text-brand-600 dark:hover:text-brand-400">Galerie</button>
                        <div class="absolute left-0 top-full hidden group-hover:block pt-2 w-48">
                            <div class="glass-card p-2">
                                <a href="{{ route('gallery.index', 'photo') }}" class="block rounded-lg px-3 py-2 hover:bg-brand-50 dark:hover:bg-white/5">Photos</a>
                                <a href="{{ route('gallery.index', 'video') }}" class="block rounded-lg px-3 py-2 hover:bg-brand-50 dark:hover:bg-white/5">Vidéos</a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('shop.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400">Boutique</a>
                    <a href="{{ route('library.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400">Bibliothèque</a>
                    <a href="{{ route('quiz.landing') }}" class="hover:text-brand-600 dark:hover:text-brand-400">MOUNGO TIC QUIZZ</a>
                    <a href="{{ route('businesses.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400">Entreprises</a>
                    <a href="{{ route('contact') }}" class="hover:text-brand-600 dark:hover:text-brand-400">Contact</a>
                </div>

                <div class="flex items-center gap-3">
                    <button @click="dark = !dark" class="rounded-full p-2 hover:bg-gray-100 dark:hover:bg-white/10" aria-label="Basculer le thème">
                        <span x-show="!dark">🌙</span>
                        <span x-show="dark">☀️</span>
                    </button>

                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex btn-primary !py-2 !px-4 text-xs">Mon espace</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-medium hover:text-brand-600">Connexion</a>
                        <a href="{{ route('register') }}" class="hidden sm:inline-flex btn-primary !py-2 !px-4 text-xs">Créer un compte</a>
                    @endauth

                    <button @click="mobileNav = !mobileNav" class="lg:hidden rounded-lg p-2 hover:bg-gray-100 dark:hover:bg-white/10" aria-label="Menu">☰</button>
                </div>
            </nav>

            <div x-show="mobileNav" x-cloak class="lg:hidden border-t border-gray-200/70 dark:border-white/10 px-4 py-4 space-y-2 text-sm">
                <a href="{{ route('articles.index') }}" class="block py-1">Actualités</a>
                <a href="{{ route('events.index') }}" class="block py-1">Événements</a>
                <a href="{{ route('gallery.index', 'photo') }}" class="block py-1">Galerie</a>
                <a href="{{ route('shop.index') }}" class="block py-1">Boutique</a>
                <a href="{{ route('library.index') }}" class="block py-1">Bibliothèque</a>
                <a href="{{ route('quiz.landing') }}" class="block py-1">MOUNGO TIC QUIZZ</a>
                <a href="{{ route('businesses.index') }}" class="block py-1">Entreprises</a>
                <a href="{{ route('contact') }}" class="block py-1">Contact</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block py-1 font-semibold text-brand-700 dark:text-brand-400">Mon espace</a>
                @else
                    <a href="{{ route('login') }}" class="block py-1">Connexion</a>
                    <a href="{{ route('register') }}" class="block py-1 font-semibold text-brand-700 dark:text-brand-400">Créer un compte</a>
                @endauth
            </div>
        </header>

        @if (session('success'))
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
                <div class="rounded-xl bg-accent-50 dark:bg-accent-900/30 text-accent-800 dark:text-accent-200 px-4 py-3 text-sm font-medium">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
                <div class="rounded-xl bg-red-50 dark:bg-red-900/30 text-red-800 dark:text-red-200 px-4 py-3 text-sm font-medium">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <main>
            {{ $slot }}
        </main>

        <footer class="mt-24 border-t border-gray-200/70 dark:border-white/10 bg-gray-50 dark:bg-slate-900/60">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 text-sm">
                <div>
                    <p class="font-extrabold text-lg text-brand-700 dark:text-brand-400">CEIMO</p>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Cercle des Enseignants d'Informatique du Moungo.</p>
                </div>
                <div>
                    <p class="font-semibold mb-2">Plateforme</p>
                    <ul class="space-y-1 text-gray-600 dark:text-gray-400">
                        <li><a href="{{ route('library.index') }}" class="hover:text-brand-600">Bibliothèque numérique</a></li>
                        <li><a href="{{ route('quiz.landing') }}" class="hover:text-brand-600">MOUNGO TIC QUIZZ</a></li>
                        <li><a href="{{ route('shop.index') }}" class="hover:text-brand-600">Boutique</a></li>
                        <li><a href="{{ route('businesses.index') }}" class="hover:text-brand-600">Entreprises membres</a></li>
                    </ul>
                </div>
                <div>
                    <p class="font-semibold mb-2">Informations</p>
                    <ul class="space-y-1 text-gray-600 dark:text-gray-400">
                        <li><a href="{{ route('faq.index') }}" class="hover:text-brand-600">FAQ</a></li>
                        <li><a href="{{ route('pages.show', 'politique-de-confidentialite') }}" class="hover:text-brand-600">Politique de confidentialité</a></li>
                        <li><a href="{{ route('pages.show', 'conditions-d-utilisation') }}" class="hover:text-brand-600">Conditions d'utilisation</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-brand-600">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <p class="font-semibold mb-2">Suivez-nous</p>
                    <p class="text-gray-600 dark:text-gray-400">WhatsApp · Facebook · LinkedIn</p>
                </div>
            </div>
            <div class="border-t border-gray-200/70 dark:border-white/10 py-4 text-center text-xs text-gray-500">
                &copy; {{ now()->year }} CEIMO — Tous droits réservés.
            </div>
        </footer>

        <livewire:ai.assistant-widget />
    </body>
</html>
