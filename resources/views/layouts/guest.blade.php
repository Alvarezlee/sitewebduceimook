<!DOCTYPE html>
<html lang="fr" x-data="{ dark: localStorage.getItem('ceimo-theme') === 'dark' }" x-init="$watch('dark', v => { localStorage.setItem('ceimo-theme', v ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', v) }); document.documentElement.classList.toggle('dark', dark)">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12 bg-gradient-to-br from-brand-50 via-white to-accent-50 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900">
            <button @click="dark = !dark" class="fixed top-6 right-6 rounded-full p-2 bg-white/70 dark:bg-white/10 hover:bg-gray-100 dark:hover:bg-white/20" aria-label="Basculer le thème">
                <span x-show="!dark">🌙</span>
                <span x-show="dark">☀️</span>
            </button>

            <a href="{{ route('home') }}" wire:navigate class="font-extrabold text-2xl tracking-tight text-brand-700 dark:text-brand-400">
                CEIMO
            </a>

            <div class="w-full sm:max-w-md mt-6 glass-card px-6 py-8">
                {{ $slot }}
            </div>

            <a href="{{ route('home') }}" wire:navigate class="mt-6 text-sm text-gray-500 hover:text-brand-600">← Retour au site</a>
        </div>
    </body>
</html>
