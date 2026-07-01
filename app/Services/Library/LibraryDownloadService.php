<?php

namespace App\Services\Library;

use App\Models\Library\LibraryDocument;
use App\Models\Library\LibrarySubscription;
use App\Models\User;
use RuntimeException;

class LibraryDownloadService
{
    /**
     * Vérifie l'éligibilité de l'utilisateur, décrémente le quota et
     * journalise le téléchargement. Lève une exception si l'accès est refusé
     * (revérifié ici même si l'URL est signée, car l'abonnement/quota peut
     * avoir changé entre l'affichage du lien et le clic).
     */
    public function authorizeAndLog(User $user, LibraryDocument $document, string $ipAddress): ?LibrarySubscription
    {
        $subscription = null;

        if (! $document->is_free) {
            $subscription = LibrarySubscription::query()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->where('ends_at', '>=', now())
                ->with('plan')
                ->latest('ends_at')
                ->first();

            if (! $subscription || ! $subscription->hasRemainingDownloads()) {
                throw new RuntimeException('Aucun abonnement actif avec des téléchargements restants.');
            }

            $subscription->increment('downloads_used');
        }

        $document->increment('downloads_count');

        $document->downloads()->create([
            'user_id' => $user->id,
            'library_subscription_id' => $subscription?->id,
            'ip_address' => $ipAddress,
            'downloaded_at' => now(),
        ]);

        return $subscription;
    }
}
