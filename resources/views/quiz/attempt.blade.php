<x-site-layout title="Tentative en cours">
    @if (session('error'))
        <div class="mx-auto max-w-3xl px-4 py-4 text-sm text-red-600">{{ session('error') }}</div>
    @endif

    <livewire:quiz.attempt-runner :edition="$edition" />
</x-site-layout>
