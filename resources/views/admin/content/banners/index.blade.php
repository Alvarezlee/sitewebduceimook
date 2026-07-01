<x-admin-layout title="Bannières / Carrousels">
    <a href="{{ route('admin.content.banners.create') }}" class="btn-primary mb-6 inline-flex">Nouvelle bannière</a>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Titre</th><th class="p-3">Position</th><th class="p-3">Active</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($banners as $banner)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $banner->title }}</td>
                        <td class="p-3">{{ $banner->position }}</td>
                        <td class="p-3">{{ $banner->is_active ? 'Oui' : 'Non' }}</td>
                        <td class="p-3 flex gap-3">
                            <a href="{{ route('admin.content.banners.edit', $banner) }}" class="text-brand-600 hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.content.banners.destroy', $banner) }}" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $banners->links() }}</div>
</x-admin-layout>
