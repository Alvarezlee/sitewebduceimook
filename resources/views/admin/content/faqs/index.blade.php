<x-admin-layout title="FAQ">
    <a href="{{ route('admin.content.faqs.create') }}" class="btn-primary mb-6 inline-flex">Nouvelle question</a>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Question</th><th class="p-3">Catégorie</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($faqs as $faq)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $faq->question }}</td>
                        <td class="p-3">{{ $faq->category }}</td>
                        <td class="p-3 flex gap-3">
                            <a href="{{ route('admin.content.faqs.edit', $faq) }}" class="text-brand-600 hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.content.faqs.destroy', $faq) }}" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $faqs->links() }}</div>
</x-admin-layout>
