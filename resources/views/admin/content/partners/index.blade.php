<x-admin-layout title="Partenaires">
    <a href="{{ route('admin.content.partners.create') }}" class="btn-primary mb-6 inline-flex">Nouveau partenaire</a>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Nom</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($partners as $partner)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $partner->name }}</td>
                        <td class="p-3 flex gap-3">
                            <a href="{{ route('admin.content.partners.edit', $partner) }}" class="text-brand-600 hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.content.partners.destroy', $partner) }}" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $partners->links() }}</div>
</x-admin-layout>
