<x-admin-layout title="Journaux">
    <h2 class="font-bold mb-4">Connexions récentes</h2>
    <div class="glass-card overflow-hidden mb-10">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Utilisateur</th><th class="p-3">IP</th><th class="p-3">Statut</th><th class="p-3">Date</th></tr>
            </thead>
            <tbody>
                @foreach ($loginLogs as $log)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $log->user?->full_name ?? $log->email_attempted }}</td>
                        <td class="p-3">{{ $log->ip_address }}</td>
                        <td class="p-3 {{ $log->status === 'success' ? 'text-emerald-600' : 'text-red-600' }}">{{ $log->status }}</td>
                        <td class="p-3">{{ $log->created_at->translatedFormat('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2 class="font-bold mb-4">Journal d'audit</h2>
    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Utilisateur</th><th class="p-3">Action</th><th class="p-3">Date</th></tr>
            </thead>
            <tbody>
                @foreach ($auditLogs as $log)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $log->user?->full_name ?? '—' }}</td>
                        <td class="p-3">{{ $log->action }}</td>
                        <td class="p-3">{{ $log->created_at->translatedFormat('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>
