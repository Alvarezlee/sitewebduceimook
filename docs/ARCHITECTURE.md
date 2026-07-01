# CEIMO — Architecture Technique

Cercle des Enseignants d'Informatique du Moungo — Plateforme officielle.

## 1. Vue d'ensemble

CEIMO est une plateforme modulaire construite sur **Laravel 12 / PHP 8.4**, avec un
frontend hybride **Blade + Livewire 3 + AlpineJS + TailwindCSS**, une base de
données **MySQL 8**, et une architecture orientée **modules indépendants** afin que
chaque fonctionnalité (bibliothèque numérique, quiz, boutique, entreprises
membres, etc.) puisse évoluer, être testée et déployée sans impacter les autres.

```
Visiteur / Élève / Enseignant / Partenaire / Admin
                │
        ┌───────┴────────┐
        │   Reverse Proxy │  (Nginx + HTTPS + cache statique)
        └───────┬────────┘
                │
        ┌───────┴────────┐
        │  Laravel App    │  Blade/Livewire (SSR) + API REST (Sanctum)
        │  (PHP-FPM 8.4)  │
        └───┬───────┬────┘
            │       │
   ┌────────┘       └─────────┐
┌──────────┐            ┌──────────────┐
│  MySQL 8  │            │ Redis (cache, │
│ (données) │            │ queues, sess.)│
└──────────┘            └──────────────┘
            │
   ┌────────┴─────────────────────────────┐
   │  Services externes (adaptateurs)      │
   │  - Monetbil (Mobile Money)             │
   │  - Fournisseur IA (QCM / Assistant)    │
   │  - Email (SMTP / Mailgun / SES)        │
   │  - WhatsApp Business API               │
   │  - SMS Gateway                         │
   │  - Firebase Cloud Messaging (Push)     │
   └────────────────────────────────────────┘
```

## 2. Stack technique

| Couche              | Choix                                                              |
|---------------------|---------------------------------------------------------------------|
| Langage / Framework | PHP 8.4, Laravel 12                                                  |
| Base de données     | MySQL 8 (InnoDB, utf8mb4)                                            |
| Cache / Queues      | Redis (optionnel en dev, recommandé en prod), driver `database` en repli |
| Frontend            | Blade, Livewire 3, AlpineJS, TailwindCSS 3, Vite                      |
| API                 | API REST (`routes/api.php`), Laravel Sanctum (tokens SPA/mobile)      |
| Autorisations       | spatie/laravel-permission (RBAC : rôles + permissions)                |
| Paiement            | Monetbil Mobile Money API (service adapter `App\Services\Payment`)    |
| PDF                 | barryvdh/laravel-dompdf (factures, certificats)                       |
| IA                  | Adaptateur `App\Services\AI` (OpenAI-compatible, interchangeable)      |
| Notifications       | Canaux Laravel natifs (mail, database) + adaptateurs WhatsApp/SMS/FCM |
| PWA                 | Manifest + Service Worker (assets/pwa)                                 |
| Tests               | Pest / PHPUnit, factories, base de données de test SQLite en mémoire  |
| Qualité             | PSR-12, Laravel Pint, PHPStan (niveau raisonnable), SOLID              |

## 3. Principes directeurs

1. **Modularité stricte** : chaque domaine métier (bibliothèque, quiz, boutique,
   entreprises, actualités, notifications, paiements) est isolé dans son propre
   espace de noms sous `app/Domain/<Module>` avec ses propres Models,
   Services, Actions, Policies et Requests. Les contrôleurs restent fins et
   délèguent aux services applicatifs.
2. **Sécurité par défaut** : validation systématique via `FormRequest`,
   autorisation via `Policy`/Gate, CSRF actif partout, échappement Blade
   (anti-XSS), requêtes Eloquent/Query Builder paramétrées (anti-SQLi),
   rate limiting sur les routes sensibles, CAPTCHA sur formulaires publics.
3. **Paiement centralisé** : un seul `PaymentService` avec un contrat
   `PaymentGatewayInterface` implémenté par `MonetbilGateway`, réutilisé par
   les 3 modules payants (abonnement bibliothèque, inscription quiz, boutique).
4. **IA remplaçable** : `AIProviderInterface` avec une implémentation par
   défaut (fournisseur configurable via `.env`), utilisée à la fois pour la
   génération de QCM et l'assistant conversationnel, afin de ne pas coupler le
   code métier à un fournisseur particulier.
5. **Notifications multicanal** : classes `Notification` Laravel avec canaux
   `mail`, `database`, `broadcast` + canaux personnalisés `whatsapp`, `sms`,
   `fcm` (adaptateurs), pilotables par préférence utilisateur.
6. **Progressivité** : livrable par étapes (architecture → BDD → migrations →
   modèles → contrôleurs → reste), chaque étape testable indépendamment.

## 4. Arborescence applicative

```
app/
  Console/Commands/
  Domain/
    Auth/                     (2FA, journal de connexions)
    Library/                  (Bibliothèque numérique)
      Models/ Services/ Policies/
    Quiz/                     (MOUNGO TIC QUIZZ)
      Models/ Services/ Policies/ Support/ (anti-cheat, scoring)
    Shop/                     (Boutique e-commerce)
      Models/ Services/ Policies/
    Business/                 (Entreprises des membres)
      Models/ Services/ Policies/
    Content/                  (Actualités, Evénements, Galerie, Pages, FAQ, Témoignages)
      Models/ Services/
    Payment/                  (Paiement Monetbil, transactions unifiées)
      Contracts/ Gateways/ Services/
    Notification/             (Notifications multicanal)
      Channels/ Services/
    AI/                       (Assistant IA + génération QCM)
      Contracts/ Providers/ Services/
    Cms/                      (Menus, bannières, carrousels, partenaires, paramètres, SEO)
      Models/ Services/
  Http/
    Controllers/
      Admin/                  (Back-office)
      Api/                    (API REST)
      Site/                   (Frontend public)
    Middleware/
    Requests/
    Resources/
  Livewire/                   (Composants interactifs : quiz runner, panier, etc.)
  Models/                     (Modèles transverses : User, Role, AuditLog…)
  Policies/
  Providers/
database/
  migrations/
  seeders/
  factories/
resources/
  views/ (site/, admin/, livewire/, emails/, pdf/)
  js/ css/
routes/
  web.php  admin.php  api.php  channels.php  console.php
docs/
  ARCHITECTURE.md  DATABASE.md  SECURITY.md  API.md
```

## 5. Modules fonctionnels (référence RF-001 à RF-011 du PRD)

| Module                     | Description courte                                                   |
|----------------------------|------------------------------------------------------------------------|
| Authentification (RF-001)  | Inscription/connexion, vérif. email, MFA (TOTP), journal de connexions |
| Profils (RF-002)           | Gestion profils par rôle (élève, enseignant, entreprise, admin)         |
| Paiement (RF-003)          | Monetbil, transactions unifiées, webhooks, statuts                      |
| Bibliothèque (RF-004)      | Catégories, documents, abonnements, téléchargements sécurisés, quotas   |
| MOUNGO TIC QUIZZ (RF-005)  | Candidats, banque de questions (IA/enseignant), sessions, classement    |
| Boutique (RF-006)          | Produits, commandes, factures PDF                                       |
| Entreprises membres (RF-007)| Vitrines entreprises, services, portfolio, contact                      |
| Actualités (RF-008)        | Articles, catégories, tags, SEO                                        |
| Galerie (RF-009)           | Photos / vidéos, albums                                                |
| Notifications (RF-010)     | Email, WhatsApp, SMS, Push/FCM                                          |
| Assistant IA (RF-011)      | Chat visiteur, recommandations, aide à l'achat                         |

## 6. Rôles (RBAC)

`super_admin`, `admin`, `moderateur`, `enseignant`, `eleve`, `entreprise`,
`partenaire`, `visiteur` (invité, aucun compte). Permissions granulaires par
ressource via `spatie/laravel-permission` (ex : `library.documents.manage`,
`quiz.questions.create`, `shop.orders.view`, `business.profile.edit`).

## 7. Flux clés

### 7.1 Paiement générique (Monetbil)
1. L'utilisateur choisit une offre (abonnement / inscription quiz / produit).
2. `PaymentService::initiate()` crée un enregistrement `payments` (statut
   `pending`) et appelle `MonetbilGateway` pour obtenir une URL/redirection de
   paiement.
3. Monetbil notifie l'application via webhook (`POST /api/webhooks/monetbil`).
4. `MonetbilWebhookController` vérifie la signature, met à jour `payments`, et
   déclenche l'événement `PaymentConfirmed` → écouteurs par module (active
   l'abonnement, débloque l'accès quiz, valide la commande + génère la facture).

### 7.2 Bibliothèque numérique
Inscription → paiement abonnement → `LibrarySubscription` actif (durée,
quota de téléchargements défini par l'admin) → téléchargement sécurisé
(URL signée temporaire + décrément de quota + `download_logs`).

### 7.3 MOUNGO TIC QUIZZ
Inscription candidat (infos personnelles + établissement + parent) → paiement
→ génération/sélection d'une session de quiz (questions aléatoires parmi la
banque, générées par IA ou par un enseignant) → passage (chronomètre,
anti-triche : verrouillage de tentative unique, détection de changement
d'onglet côté client, sauvegarde auto des réponses) → correction (auto pour
QCM, manuelle si question ouverte) → classement → certificat PDF.

### 7.4 Boutique
Catalogue → panier (session/Livewire) → commande → paiement Monetbil →
facture PDF générée et associée à la commande → téléchargement/livraison
numérique si applicable.

## 8. Sécurité (détails dans `docs/SECURITY.md`)

CSRF (natif Laravel), XSS (échappement Blade + CSP headers), SQLi (Eloquent /
requêtes préparées, jamais de SQL brut concaténé), rate limiting (`throttle`
middleware sur login, formulaires, API), CAPTCHA (Google reCAPTCHA v3) sur
formulaires publics, MFA TOTP optionnelle/obligatoire par rôle, journal de
connexions (`login_logs`), journal d'audit (`audit_logs`), sauvegardes
automatiques (spatie/laravel-backup).

## 9. SEO

Slugs sur tout contenu public, sitemap dynamique (`spatie/laravel-sitemap`),
`robots.txt`, balises meta/Open Graph/Twitter Card par page, JSON-LD
Schema.org (Organization, Article, Product, FAQPage, Event), Google
Analytics / Search Console configurables via panneau admin.

## 10. Performance

Cache de requêtes/vues (Redis), lazy loading d'images, compression (gzip via
serveur web), pagination systématique sur listes, files d'attente (queues)
pour emails, notifications, génération PDF, appels IA, webhooks.

## 11. Roadmap de livraison

1. Architecture (ce document) + schéma BDD — **fait en premier, avant tout contrôleur**.
2. Migrations, modèles, factories, seeders.
3. Authentification, RBAC, middleware, routes.
4. Contrôleurs/services par module (site public → bibliothèque → quiz →
   boutique → entreprises → administration).
5. Paiement Monetbil, IA, notifications.
6. Frontend public + panneau admin.
7. Tests automatisés.
8. Documentation finale.
