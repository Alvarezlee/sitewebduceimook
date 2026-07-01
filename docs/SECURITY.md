# CEIMO — Politique de sécurité applicative

Référence pour l'implémentation (OWASP Top 10) — voir `docs/ARCHITECTURE.md`.

## 1. Authentification & sessions
- Hachage des mots de passe : `bcrypt`/`argon2id` (défaut Laravel).
- MFA (TOTP) optionnelle pour tous, **obligatoire** pour `admin`/`super_admin`.
- Verrouillage progressif après échecs répétés (`login_logs` + throttle
  `5/minute` par IP+email sur `POST /login`).
- Sessions : cookie `HttpOnly`, `Secure`, `SameSite=Lax`, régénération d'ID
  de session à chaque connexion.
- Journalisation de toutes les tentatives (succès/échec) dans `login_logs`.

## 2. Autorisation
- RBAC via `spatie/laravel-permission`. Chaque contrôleur/action protégé par
  middleware `role:` / `permission:` ou `Policy` Eloquent.
- Vérification systématique de la propriété des ressources (ex : un
  enseignant ne modifie que ses propres questions, une entreprise que sa
  propre vitrine) via Policies.

## 3. Injections (SQL, XSS, CSRF)
- **SQLi** : uniquement Eloquent ORM / Query Builder paramétré. Aucune
  requête SQL brute concaténée avec une entrée utilisateur.
- **XSS** : échappement Blade `{{ }}` par défaut ; `{!! !!}` interdit sauf
  contenu passé par un éditeur WYSIWYG nettoyé (`HTMLPurifier`). En-tête
  `Content-Security-Policy` restrictif.
- **CSRF** : middleware `VerifyCsrfToken` actif sur toutes les routes web ;
  jeton CSRF sur tous les formulaires ; API stateless protégée par Sanctum.

## 4. Validation des entrées
- Toute entrée utilisateur validée via `FormRequest` dédiée (règles strictes
  de type, longueur, format — jamais de validation uniquement côté client).
- Upload de fichiers : whitelist d'extensions/MIME, taille max, scan de nom
  de fichier (slug généré côté serveur), stockage hors `public/` direct pour
  les documents payants (bibliothèque), accès via route signée temporaire.

## 5. Rate limiting & anti-bot
- `throttle` middleware sur : login, inscription, mot de passe oublié,
  formulaire de contact, API publique, tentative de quiz.
- CAPTCHA (Google reCAPTCHA v3) sur inscription, contact, formulaire
  entreprise.

## 6. Téléchargements sécurisés (bibliothèque)
- URL de téléchargement signée (`URL::temporarySignedRoute`, validité
  courte, ex. 5 minutes) + vérification quota restant + abonnement actif au
  moment du clic (pas seulement à l'affichage).
- Chaque téléchargement journalisé (`library_downloads`) avec IP et horodatage.

## 7. Anti-triche Quiz
- Une tentative unique par candidat et par édition (contrainte UNIQUE en
  base + vérification serveur, jamais uniquement côté client).
- Chronomètre serveur (`expires_at` en base) : soumission automatique à
  expiration, revérifiée côté serveur à chaque requête (jamais de confiance
  au timer JS seul).
- Questions et options mélangées aléatoirement par tentative (seed stockée).
- Sauvegarde automatique des réponses (auto-save Livewire à chaque réponse).
- Détection de changement d'onglet / perte de focus côté client, remontée
  dans `anti_cheat_flags` (JSON) pour revue par un modérateur — ne bloque pas
  automatiquement (évite faux positifs) mais signale pour audit humain.

## 8. Paiements
- Aucune donnée de carte/mobile money stockée localement (délégué à
  Monetbil). Seules les références de transaction sont conservées.
- Vérification de signature/callback Monetbil avant toute mise à jour de
  statut (jamais de confiance sur un simple retour navigateur/redirect).
- Idempotence des webhooks (clé `gateway_reference` UNIQUE).

## 9. Sauvegardes & journalisation
- Sauvegardes automatiques planifiées (`spatie/laravel-backup`), rotation et
  stockage hors serveur applicatif (S3-compatible).
- `audit_logs` : traçabilité des actions sensibles d'administration (CRUD
  contenus, changements de rôle, changements de statut paiement).

## 10. En-têtes HTTP & transport
- HTTPS obligatoire (HSTS), `X-Content-Type-Options: nosniff`,
  `X-Frame-Options: DENY` (ou `frame-ancestors 'none'` via CSP),
  `Referrer-Policy: strict-origin-when-cross-origin`.

## 11. Secrets
- Toutes les clés (Monetbil, IA, FCM, SMTP, reCAPTCHA) exclusivement en
  variables d'environnement (`.env`, jamais committées) ; `.env.example`
  fourni avec des placeholders documentés.
