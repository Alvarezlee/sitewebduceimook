<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tableau de bord
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Bienvenue, {{ auth()->user()->first_name }} 👋
                </h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Voici votre espace membre CEIMO. Retrouvez ici un accès rapide à la bibliothèque numérique, au MOUNGO TIC QUIZZ, à vos commandes et à votre profil.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="{{ route('library.index') }}" wire:navigate class="glass-card p-6 hover:-translate-y-0.5 transition">
                    <div class="text-3xl">📚</div>
                    <h4 class="mt-3 font-semibold text-gray-900 dark:text-gray-100">Bibliothèque numérique</h4>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Consultez et téléchargez les documents disponibles selon votre abonnement.</p>
                </a>

                <a href="{{ route('quiz.landing') }}" wire:navigate class="glass-card p-6 hover:-translate-y-0.5 transition">
                    <div class="text-3xl">🏆</div>
                    <h4 class="mt-3 font-semibold text-gray-900 dark:text-gray-100">MOUNGO TIC QUIZZ</h4>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Inscrivez-vous à une édition, tentez le quiz et suivez le classement.</p>
                </a>

                <a href="{{ route('shop.orders.index') }}" wire:navigate class="glass-card p-6 hover:-translate-y-0.5 transition">
                    <div class="text-3xl">🛒</div>
                    <h4 class="mt-3 font-semibold text-gray-900 dark:text-gray-100">Mes commandes</h4>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Suivez l'état de vos achats effectués dans la boutique CEIMO.</p>
                </a>

                @hasanyrole('enseignant|admin|super_admin')
                    <a href="{{ route('teacher.questions.index') }}" wire:navigate class="glass-card p-6 hover:-translate-y-0.5 transition">
                        <div class="text-3xl">📝</div>
                        <h4 class="mt-3 font-semibold text-gray-900 dark:text-gray-100">Banque de questions</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Créez et validez des questions pour le MOUNGO TIC QUIZZ.</p>
                    </a>
                @endhasanyrole

                @role('entreprise')
                    <a href="{{ route('business.dashboard.edit') }}" wire:navigate class="glass-card p-6 hover:-translate-y-0.5 transition">
                        <div class="text-3xl">🏢</div>
                        <h4 class="mt-3 font-semibold text-gray-900 dark:text-gray-100">Mon entreprise</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Gérez la vitrine, les services et les médias de votre entreprise.</p>
                    </a>
                @endrole

                @hasanyrole('admin|super_admin|moderateur')
                    <a href="{{ route('admin.dashboard') }}" wire:navigate class="glass-card p-6 hover:-translate-y-0.5 transition">
                        <div class="text-3xl">⚙️</div>
                        <h4 class="mt-3 font-semibold text-gray-900 dark:text-gray-100">Administration</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Accédez au panneau d'administration de la plateforme.</p>
                    </a>
                @endhasanyrole

                <a href="{{ route('profile') }}" wire:navigate class="glass-card p-6 hover:-translate-y-0.5 transition">
                    <div class="text-3xl">👤</div>
                    <h4 class="mt-3 font-semibold text-gray-900 dark:text-gray-100">Mon profil</h4>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Mettez à jour vos informations personnelles et votre sécurité.</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
