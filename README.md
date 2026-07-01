# CEIMO — Plateforme officielle

Application web du **Cercle des Enseignants d'Informatique du Moungo** (CEIMO) :
formation, bibliothèque numérique payante, concours **MOUNGO TIC QUIZZ**, boutique
e-commerce, vitrines d'entreprises membres, assistant IA et panneau d'administration
complet.

## Stack technique

- **Backend** : Laravel 12 / PHP 8.4
- **Frontend** : Livewire 3 + Volt, AlpineJS, TailwindCSS (thème glassmorphism bleu/blanc/vert, mode sombre)
- **Base de données** : MySQL 8 (SQLite pour les tests automatisés)
- **Paiement** : Monetbil (Mobile Money — Orange Money / MTN MoMo)
- **Notifications** : Email, WhatsApp, SMS, Push (FCM)
- **IA** : fournisseur configurable (OpenAI par défaut) pour l'assistant conversationnel et la génération de QCM
- **RBAC** : spatie/laravel-permission (rôles : super_admin, admin, moderateur, enseignant, eleve, entreprise, partenaire)
- **Sécurité** : 2FA (TOTP), reCAPTCHA v3, rate limiting, audit log
- **Tests** : Pest

Voir [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md), [docs/DATABASE.md](docs/DATABASE.md)
et [docs/SECURITY.md](docs/SECURITY.md) pour la documentation technique détaillée.

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configurer `.env` (base de données, Monetbil, IA, notifications — voir la section
[Variables d'environnement](#variables-denvironnement) ci-dessous), puis :

```bash
php artisan migrate --seed
npm install
npm run build   # ou `npm run dev` en développement
php artisan storage:link
php artisan serve
```

## Comptes de démonstration

Après `php artisan migrate --seed`, les comptes suivants sont disponibles
(mot de passe : `password`) :

| Rôle | Email |
|---|---|
| Super administrateur | `superadmin@ceimo.cm` |
| Administrateur | `admin@ceimo.cm` |
| Entreprise membre | `contact@al-infotech.cm` |

Des enseignants et élèves supplémentaires sont générés aléatoirement par
`UserSeeder`.

## Variables d'environnement

| Groupe | Variables clés |
|---|---|
| Paiement Monetbil | `MONETBIL_SERVICE_KEY`, `MONETBIL_SERVICE_SECRET`, `MONETBIL_NOTIFY_URL` |
| Assistant IA / génération QCM | `AI_PROVIDER`, `AI_API_KEY`, `AI_MODEL`, `AI_BASE_URL` |
| Notifications WhatsApp / SMS | `WHATSAPP_API_URL`, `WHATSAPP_API_TOKEN`, `SMS_GATEWAY_URL`, `SMS_GATEWAY_API_KEY` |
| Notifications Push (FCM) | `FCM_PROJECT_ID`, `FCM_CREDENTIALS_PATH` |
| Anti-bot | `RECAPTCHA_SITE_KEY`, `RECAPTCHA_SECRET_KEY` |
| SEO / Analytics | `GOOGLE_ANALYTICS_ID`, `GOOGLE_SITE_VERIFICATION` |

Chaque intégration est optionnelle en développement : si une clé n'est pas
renseignée (ex. `RECAPTCHA_SITE_KEY`), la fonctionnalité correspondante est
désactivée sans bloquer le reste de l'application.

## Tests

```bash
php artisan test
```

La suite Pest couvre l'authentification, la RBAC, chaque module métier
(bibliothèque, quiz, boutique, entreprises), le paiement Monetbil (webhook et
signature), les notifications, la sécurité (reCAPTCHA, rate limiting) et le SEO.

## Qualité de code

```bash
./vendor/bin/pint
```

## Modules principaux

- **Bibliothèque numérique** — abonnements payants, téléchargements sécurisés à quota
- **MOUNGO TIC QUIZZ** — QCM générés par IA ou créés par les enseignants, chronomètre serveur, anti-triche, classement automatique, certificats PDF
- **Boutique** — panier, commande, paiement Monetbil, facture PDF
- **Entreprises membres** — vitrines, services, galerie média
- **Assistant IA** — widget conversationnel présent sur tout le site
- **Panneau d'administration** — gestion complète du contenu, des utilisateurs, des paiements et des paramètres du site

## Licence

Propriété du CEIMO. Tous droits réservés.
