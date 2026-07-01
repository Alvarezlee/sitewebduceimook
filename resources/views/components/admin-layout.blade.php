@props(['title' => null])
<!DOCTYPE html>
<html lang="fr" class="h-full" x-data="{ dark: localStorage.getItem('ceimo-theme') === 'dark' }" x-init="$watch('dark', v => { localStorage.setItem('ceimo-theme', v ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', v) }); document.documentElement.classList.toggle('dark', dark)">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Administration' }} — {{ config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full bg-gray-50 dark:bg-slate-950 font-sans antialiased text-gray-900 dark:text-gray-100">
        <div class="min-h-full flex">
            <!-- Sidebar -->
            <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:shrink-0 border-r border-gray-200/70 dark:border-white/10 bg-white/80 dark:bg-slate-900/60 backdrop-blur-xl">
                <div class="h-16 flex items-center px-6 border-b border-gray-200/70 dark:border-white/10">
                    <a href="{{ route('admin.dashboard') }}" class="font-bold text-lg tracking-tight text-blue-700 dark:text-blue-400">CEIMO <span class="text-emerald-500">Admin</span></a>
                </div>
                <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 text-sm">
                    @php
                        $adminNav = [
                            ['label' => 'Tableau de bord', 'route' => 'admin.dashboard'],
                            ['label' => 'Utilisateurs', 'route' => 'admin.users.index'],
                            ['label' => 'Bibliothèque', 'route' => 'admin.library.documents.index'],
                            ['label' => 'MOUNGO TIC QUIZZ', 'route' => 'admin.quiz.editions.index'],
                            ['label' => 'Boutique', 'route' => 'admin.shop.products.index'],
                            ['label' => 'Entreprises', 'route' => 'admin.businesses.index'],
                            ['label' => 'Actualités', 'route' => 'admin.content.articles.index'],
                            ['label' => 'Paiements', 'route' => 'admin.payments.index'],
                            ['label' => 'Paramètres', 'route' => 'admin.settings.index'],
                        ];
                    @endphp
                    @foreach ($adminNav as $item)
                        <a href="{{ \Illuminate\Support\Facades\Route::has($item['route']) ? route($item['route']) : '#' }}"
                           class="block rounded-lg px-3 py-2 font-medium hover:bg-blue-50 dark:hover:bg-white/5 {{ request()->routeIs(explode('.index', $item['route'])[0].'*') ? 'bg-blue-50 dark:bg-white/10 text-blue-700 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
                <div class="p-4 border-t border-gray-200/70 dark:border-white/10">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left text-sm font-medium text-red-600 dark:text-red-400 hover:underline">Déconnexion</button>
                    </form>
                </div>
            </aside>

            <div class="flex-1 flex flex-col min-w-0">
                <header class="h-16 flex items-center justify-between px-4 lg:px-8 border-b border-gray-200/70 dark:border-white/10 bg-white/80 dark:bg-slate-900/60 backdrop-blur-xl">
                    <h1 class="text-lg font-semibold">{{ $title ?? 'Tableau de bord' }}</h1>
                    <button @click="dark = !dark" class="rounded-full p-2 hover:bg-gray-100 dark:hover:bg-white/10" aria-label="Basculer le thème">
                        <span x-show="!dark">🌙</span>
                        <span x-show="dark">☀️</span>
                    </button>
                </header>

                <main class="flex-1 p-4 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
