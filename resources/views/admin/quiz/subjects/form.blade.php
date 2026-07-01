<x-admin-layout :title="$subject->exists ? 'Modifier le sujet' : 'Nouveau sujet'">
    <form method="POST" action="{{ $subject->exists ? route('admin.quiz.subjects.update', $subject) : route('admin.quiz.subjects.store') }}" class="glass-card p-6 max-w-xl space-y-4">
        @csrf
        @if ($subject->exists) @method('PUT') @endif

        <div>
            <x-input-label for="name" value="Nom" />
            <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $subject->name)" required />
        </div>
        <div>
            <x-input-label for="description" value="Description" />
            <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10">{{ old('description', $subject->description) }}</textarea>
        </div>

        <x-primary-button>Enregistrer</x-primary-button>
    </form>
</x-admin-layout>
