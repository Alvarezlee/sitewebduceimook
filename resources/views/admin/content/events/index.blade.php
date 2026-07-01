<x-admin-layout title="Événements">
    <a href="{{ route('admin.content.events.create') }}" class="btn-primary mb-6 inline-flex">Nouvel événement</a>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Titre</th><th class="p-3">Date</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $event->title }}</td>
                        <td class="p-3">{{ $event->starts_at->translatedFormat('d/m/Y H:i') }}</td>
                        <td class="p-3 flex gap-3">
                            <a href="{{ route('admin.content.events.edit', $event) }}" class="text-brand-600 hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.content.events.destroy', $event) }}" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $events->links() }}</div>
</x-admin-layout>
