<x-admin-layout title="Sujets Quiz">
    <a href="{{ route('admin.quiz.subjects.create') }}" class="btn-primary mb-6 inline-flex">Nouveau sujet</a>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Nom</th><th class="p-3">Questions</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($subjects as $subject)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $subject->name }}</td>
                        <td class="p-3">{{ $subject->questions_count }}</td>
                        <td class="p-3 flex gap-3">
                            <a href="{{ route('admin.quiz.subjects.edit', $subject) }}" class="text-brand-600 hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.quiz.subjects.destroy', $subject) }}" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $subjects->links() }}</div>
</x-admin-layout>
