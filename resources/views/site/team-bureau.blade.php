<x-site-layout title="Bureau Exécutif">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <span class="section-eyebrow">Gouvernance</span>
        <h1 class="section-title mt-1 mb-10">Bureau Exécutif</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($members as $member)
                <div class="glass-card p-6 text-center">
                    <div class="mx-auto h-20 w-20 rounded-full bg-brand-100 dark:bg-brand-900 flex items-center justify-center text-xl font-bold text-brand-700 dark:text-brand-300">
                        {{ mb_substr($member->user->first_name, 0, 1) }}{{ mb_substr($member->user->last_name, 0, 1) }}
                    </div>
                    <p class="mt-4 font-bold">{{ $member->user->full_name }}</p>
                    <p class="text-sm text-brand-600 dark:text-brand-400 font-semibold">{{ $member->bureau_role }}</p>
                    <p class="mt-2 text-xs text-gray-500">{{ $member->specialty }}</p>
                </div>
            @empty
                <p class="text-gray-500">Le bureau exécutif sera bientôt présenté ici.</p>
            @endforelse
        </div>
    </div>
</x-site-layout>
