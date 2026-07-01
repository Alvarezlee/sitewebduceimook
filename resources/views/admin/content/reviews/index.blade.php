<x-admin-layout title="Témoignages">
    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Nom</th><th class="p-3">Note</th><th class="p-3">Approuvé</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($reviews as $review)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $review->name }}</td>
                        <td class="p-3">{{ $review->rating }}/5</td>
                        <td class="p-3">{{ $review->is_approved ? 'Oui' : 'Non' }}</td>
                        <td class="p-3 flex gap-3">
                            <form method="POST" action="{{ route('admin.content.reviews.approve', $review) }}">
                                @csrf @method('PUT')
                                <button class="text-brand-600 hover:underline">{{ $review->is_approved ? 'Masquer' : 'Approuver' }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.content.reviews.destroy', $review) }}" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $reviews->links() }}</div>
</x-admin-layout>
