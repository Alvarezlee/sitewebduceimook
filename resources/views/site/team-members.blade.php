<x-site-layout title="Membres">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <span class="section-eyebrow">Communauté</span>
        <h1 class="section-title mt-1 mb-10">Nos enseignants membres</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($teachers as $teacher)
                <div class="glass-card p-6 text-center">
                    <div class="mx-auto h-16 w-16 rounded-full bg-accent-100 dark:bg-accent-900 flex items-center justify-center text-lg font-bold text-accent-700 dark:text-accent-300">
                        {{ mb_substr($teacher->user->first_name, 0, 1) }}{{ mb_substr($teacher->user->last_name, 0, 1) }}
                    </div>
                    <p class="mt-3 font-semibold text-sm">{{ $teacher->user->full_name }}</p>
                    <p class="text-xs text-gray-500">{{ $teacher->specialty }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $teachers->links() }}
        </div>
    </div>
</x-site-layout>
