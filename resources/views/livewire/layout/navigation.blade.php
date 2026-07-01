<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false, dark: localStorage.getItem('ceimo-theme') === 'dark' }" x-init="$watch('dark', v => { localStorage.setItem('ceimo-theme', v ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', v) })" class="bg-white/80 dark:bg-slate-950/70 backdrop-blur-xl border-b border-gray-200/70 dark:border-white/10">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate class="font-extrabold text-xl tracking-tight text-brand-700 dark:text-brand-400">
                        CEIMO
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        Tableau de bord
                    </x-nav-link>
                    <x-nav-link :href="route('library.index')" :active="request()->routeIs('library.*')" wire:navigate>
                        Bibliothèque
                    </x-nav-link>
                    <x-nav-link :href="route('quiz.landing')" :active="request()->routeIs('quiz.*')" wire:navigate>
                        MOUNGO TIC QUIZZ
                    </x-nav-link>
                    <x-nav-link :href="route('shop.orders.index')" :active="request()->routeIs('shop.orders.*')" wire:navigate>
                        Mes commandes
                    </x-nav-link>
                    @hasanyrole('enseignant|admin|super_admin')
                        <x-nav-link :href="route('teacher.questions.index')" :active="request()->routeIs('teacher.*')" wire:navigate>
                            Banque de questions
                        </x-nav-link>
                    @endhasanyrole
                    @role('entreprise')
                        <x-nav-link :href="route('business.dashboard.edit')" :active="request()->routeIs('business.dashboard.*')" wire:navigate>
                            Mon entreprise
                        </x-nav-link>
                    @endrole
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-2">
                <button @click="dark = !dark" class="rounded-full p-2 hover:bg-gray-100 dark:hover:bg-white/10" aria-label="Basculer le thème">
                    <span x-show="!dark">🌙</span>
                    <span x-show="dark">☀️</span>
                </button>

                <a href="{{ route('home') }}" wire:navigate class="rounded-md px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-brand-600 dark:hover:text-brand-400">
                    ← Retour au site
                </a>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->full_name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            Mon profil
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                Se déconnecter
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                Tableau de bord
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('library.index')" :active="request()->routeIs('library.*')" wire:navigate>
                Bibliothèque
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('quiz.landing')" :active="request()->routeIs('quiz.*')" wire:navigate>
                MOUNGO TIC QUIZZ
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('shop.orders.index')" :active="request()->routeIs('shop.orders.*')" wire:navigate>
                Mes commandes
            </x-responsive-nav-link>
            @hasanyrole('enseignant|admin|super_admin')
                <x-responsive-nav-link :href="route('teacher.questions.index')" :active="request()->routeIs('teacher.*')" wire:navigate>
                    Banque de questions
                </x-responsive-nav-link>
            @endhasanyrole
            @role('entreprise')
                <x-responsive-nav-link :href="route('business.dashboard.edit')" :active="request()->routeIs('business.dashboard.*')" wire:navigate>
                    Mon entreprise
                </x-responsive-nav-link>
            @endrole
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200" x-data="{{ json_encode(['name' => auth()->user()->full_name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    Mon profil
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        Se déconnecter
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
