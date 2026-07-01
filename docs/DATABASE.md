# CEIMO — Schéma de base de données (MCD)

Base MySQL 8, moteur InnoDB, charset `utf8mb4_unicode_ci`. Toutes les tables
utilisent des clés primaires `id` (BIGINT UNSIGNED AUTO_INCREMENT), les
horodatages `created_at`/`updated_at`, et `deleted_at` (soft delete) lorsque
pertinent. Les noms de tables sont au pluriel snake_case, conformes aux
conventions Laravel.

## 1. Identité, RBAC & sécurité

### `users`
| Colonne | Type | Contraintes |
|---|---|---|
| id | BIGINT UNSIGNED | PK |
| first_name | VARCHAR(100) | NOT NULL |
| last_name | VARCHAR(100) | NOT NULL |
| email | VARCHAR(190) | UNIQUE, NOT NULL |
| phone | VARCHAR(30) | UNIQUE NULL |
| password | VARCHAR(255) | NOT NULL |
| avatar_path | VARCHAR(255) | NULL |
| gender | ENUM('m','f') | NULL |
| birth_date | DATE | NULL |
| status | ENUM('active','suspended','pending') | DEFAULT 'pending' |
| two_factor_secret | TEXT | NULL (chiffré) |
| two_factor_recovery_codes | TEXT | NULL (chiffré) |
| two_factor_confirmed_at | TIMESTAMP | NULL |
| email_verified_at | TIMESTAMP | NULL |
| phone_verified_at | TIMESTAMP | NULL |
| last_login_at | TIMESTAMP | NULL |
| remember_token | VARCHAR(100) | NULL |
| deleted_at | TIMESTAMP | NULL |
| timestamps | | |

Relations : 1-N vers `teacher_profiles`, `student_profiles`, `businesses`,
`library_subscriptions`, `orders`, `quiz_candidates`, `login_logs`,
`audit_logs`, `notifications` (morph), `reviews`. N-N vers `roles` (via
`model_has_roles` — package spatie/laravel-permission).

### `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`
Fournies par `spatie/laravel-permission` (migration standard du package).
Rôles : `super_admin`, `admin`, `moderateur`, `enseignant`, `eleve`,
`entreprise`, `partenaire`.

### `teacher_profiles`
| Colonne | Type | Contraintes |
|---|---|---|
| id | BIGINT UNSIGNED | PK |
| user_id | BIGINT UNSIGNED | FK → users.id, UNIQUE |
| specialty | VARCHAR(150) | NULL |
| institution | VARCHAR(150) | NULL |
| bio | TEXT | NULL |
| bureau_role | VARCHAR(100) | NULL (ex : Président, Secrétaire — Bureau Exécutif) |
| bureau_order | SMALLINT UNSIGNED | NULL (ordre d'affichage) |
| is_bureau_member | BOOLEAN | DEFAULT FALSE |

### `student_profiles`
| Colonne | Type | Contraintes |
|---|---|---|
| id | BIGINT UNSIGNED | PK |
| user_id | BIGINT UNSIGNED | FK → users.id, UNIQUE |
| institution | VARCHAR(150) | NULL |
| class_level | VARCHAR(50) | NULL |
| region | VARCHAR(100) | NULL |
| department | VARCHAR(100) | NULL |
| arrondissement | VARCHAR(100) | NULL |
| parent_name | VARCHAR(150) | NULL |
| parent_phone | VARCHAR(30) | NULL |

### `login_logs`
| Colonne | Type | Contraintes |
|---|---|---|
| id | BIGINT UNSIGNED | PK |
| user_id | BIGINT UNSIGNED | FK → users.id NULL (tentative échouée sans user résolu) |
| email_attempted | VARCHAR(190) | NULL |
| ip_address | VARCHAR(45) | NOT NULL |
| user_agent | VARCHAR(255) | NULL |
| status | ENUM('success','failed') | NOT NULL |
| created_at | TIMESTAMP | |

Index : (`user_id`), (`ip_address`, `created_at`).

### `audit_logs`
| Colonne | Type | Contraintes |
|---|---|---|
| id | BIGINT UNSIGNED | PK |
| user_id | BIGINT UNSIGNED | FK → users.id NULL |
| action | VARCHAR(150) | NOT NULL (ex: `product.updated`) |
| auditable_type | VARCHAR(150) | NULL (morph) |
| auditable_id | BIGINT UNSIGNED | NULL (morph) |
| old_values | JSON | NULL |
| new_values | JSON | NULL |
| ip_address | VARCHAR(45) | NULL |
| created_at | TIMESTAMP | |

## 2. Paiement unifié

### `payments`
Table centrale utilisée par les 3 modules payants via relation polymorphique.
| Colonne | Type | Contraintes |
|---|---|---|
| id | BIGINT UNSIGNED | PK |
| user_id | BIGINT UNSIGNED | FK → users.id |
| payable_type | VARCHAR(150) | NOT NULL (morph : LibrarySubscription, QuizCandidate, Order) |
| payable_id | BIGINT UNSIGNED | NOT NULL (morph) |
| gateway | VARCHAR(50) | DEFAULT 'monetbil' |
| gateway_reference | VARCHAR(150) | NULL, UNIQUE |
| amount | DECIMAL(12,2) | NOT NULL |
| currency | VARCHAR(3) | DEFAULT 'XAF' |
| phone_number | VARCHAR(30) | NULL |
| status | ENUM('pending','success','failed','cancelled','refunded') | DEFAULT 'pending' |
| payload | JSON | NULL (réponse brute gateway) |
| paid_at | TIMESTAMP | NULL |
| timestamps | | |

Index : (`payable_type`, `payable_id`), (`status`), (`gateway_reference`).

## 3. Bibliothèque numérique

### `library_categories`
id, name, slug (UNIQUE), description, parent_id (FK auto-référence NULL),
icon, timestamps.

### `library_plans` (offres d'abonnement définies par l'admin)
id, name, price (DECIMAL 10,2), duration_days (INT), max_downloads (INT
NULL = illimité), description, is_active (BOOLEAN), timestamps.

### `library_subscriptions`
| Colonne | Type | Contraintes |
|---|---|---|
| id | BIGINT UNSIGNED | PK |
| user_id | BIGINT UNSIGNED | FK → users.id |
| library_plan_id | BIGINT UNSIGNED | FK → library_plans.id |
| starts_at | TIMESTAMP | NOT NULL |
| ends_at | TIMESTAMP | NOT NULL |
| downloads_used | INT UNSIGNED | DEFAULT 0 |
| status | ENUM('pending','active','expired','cancelled') | DEFAULT 'pending' |
| timestamps | | |

Index : (`user_id`, `status`).

### `library_documents`
| Colonne | Type | Contraintes |
|---|---|---|
| id | BIGINT UNSIGNED | PK |
| library_category_id | BIGINT UNSIGNED | FK → library_categories.id |
| title | VARCHAR(200) | NOT NULL |
| slug | VARCHAR(220) | UNIQUE |
| type | ENUM('probatoire','baccalaureat','bepc','cap','bts','concours','corrige','fascicule','livre') | NOT NULL |
| description | TEXT | NULL |
| file_path | VARCHAR(255) | NOT NULL |
| cover_path | VARCHAR(255) | NULL |
| file_size | INT UNSIGNED | NULL (octets) |
| year | SMALLINT UNSIGNED | NULL |
| is_free | BOOLEAN | DEFAULT FALSE |
| downloads_count | INT UNSIGNED | DEFAULT 0 |
| is_published | BOOLEAN | DEFAULT TRUE |
| timestamps + deleted_at | | |

Index : (`library_category_id`), (`type`), (`slug`).

### `library_downloads` (historique / quotas)
id, user_id (FK), library_document_id (FK), library_subscription_id (FK
NULL), ip_address, downloaded_at (TIMESTAMP). Index (`user_id`,
`downloaded_at`).

## 4. MOUNGO TIC QUIZZ

### `quiz_subjects` (matières : Informatique, TIC, Algorithmique, Réseaux, Programmation, Culture numérique)
id, name, slug, description, timestamps.

### `quiz_editions` (sessions/concours périodiques, ex: "Edition 2026")
id, name, registration_price (DECIMAL), starts_at, ends_at,
duration_minutes (INT), max_attempts (TINYINT DEFAULT 1), is_active
(BOOLEAN), timestamps.

### `quiz_questions`
| Colonne | Type | Contraintes |
|---|---|---|
| id | BIGINT UNSIGNED | PK |
| quiz_subject_id | BIGINT UNSIGNED | FK → quiz_subjects.id |
| created_by | BIGINT UNSIGNED | FK → users.id NULL (enseignant) |
| source | ENUM('ai','teacher') | DEFAULT 'teacher' |
| question | TEXT | NOT NULL |
| explanation | TEXT | NULL |
| difficulty | ENUM('easy','medium','hard') | DEFAULT 'medium' |
| is_open_ended | BOOLEAN | DEFAULT FALSE (question ouverte = correction manuelle) |
| is_validated | BOOLEAN | DEFAULT FALSE (validation avant mise en banque) |
| timestamps | | |

### `quiz_question_options`
id, quiz_question_id (FK), label (VARCHAR 255), is_correct (BOOLEAN),
`order` (TINYINT).

### `quiz_candidates`
| Colonne | Type | Contraintes |
|---|---|---|
| id | BIGINT UNSIGNED | PK |
| user_id | BIGINT UNSIGNED | FK → users.id |
| quiz_edition_id | BIGINT UNSIGNED | FK → quiz_editions.id |
| photo_path | VARCHAR(255) | NULL |
| institution | VARCHAR(150) | NULL |
| class_level | VARCHAR(50) | NULL |
| region | VARCHAR(100) | NULL |
| department | VARCHAR(100) | NULL |
| arrondissement | VARCHAR(100) | NULL |
| parent_name | VARCHAR(150) | NULL |
| parent_phone | VARCHAR(30) | NULL |
| registration_status | ENUM('pending','paid','cancelled') | DEFAULT 'pending' |
| timestamps | | |

Contrainte UNIQUE (`user_id`, `quiz_edition_id`).

### `quiz_attempts` (une tentative = passage du quiz)
| Colonne | Type | Contraintes |
|---|---|---|
| id | BIGINT UNSIGNED | PK |
| quiz_candidate_id | BIGINT UNSIGNED | FK → quiz_candidates.id |
| started_at | TIMESTAMP | NOT NULL |
| finished_at | TIMESTAMP | NULL |
| expires_at | TIMESTAMP | NOT NULL (chronomètre) |
| status | ENUM('in_progress','submitted','graded','flagged') | DEFAULT 'in_progress' |
| score | DECIMAL(5,2) | NULL |
| rank | INT UNSIGNED | NULL |
| anti_cheat_flags | JSON | NULL (ex: changements d'onglet détectés) |
| timestamps | | |

Contrainte UNIQUE (`quiz_candidate_id`) → garantit la tentative unique.

### `quiz_attempt_answers`
id, quiz_attempt_id (FK), quiz_question_id (FK), quiz_question_option_id
(FK NULL, si QCM), open_answer_text (TEXT NULL, si question ouverte),
is_correct (BOOLEAN NULL), points_awarded (DECIMAL 5,2 NULL), graded_by
(FK users NULL, correction manuelle), answered_at (TIMESTAMP).

### `quiz_certificates`
id, quiz_attempt_id (FK, UNIQUE), certificate_number (VARCHAR UNIQUE),
file_path (VARCHAR), issued_at (TIMESTAMP).

## 5. Boutique e-commerce

### `product_categories`
id, name, slug (UNIQUE), parent_id (auto-FK NULL), timestamps.

### `products`
| Colonne | Type | Contraintes |
|---|---|---|
| id | BIGINT UNSIGNED | PK |
| product_category_id | BIGINT UNSIGNED | FK |
| name | VARCHAR(200) | NOT NULL |
| slug | VARCHAR(220) | UNIQUE |
| type | ENUM('livre','fascicule','cours','pdf','logiciel','formation','abonnement') | NOT NULL |
| description | TEXT | NULL |
| price | DECIMAL(12,2) | NOT NULL |
| compare_at_price | DECIMAL(12,2) | NULL |
| stock | INT UNSIGNED | NULL (NULL = numérique illimité) |
| cover_path | VARCHAR(255) | NULL |
| file_path | VARCHAR(255) | NULL (livrable numérique) |
| is_digital | BOOLEAN | DEFAULT TRUE |
| is_published | BOOLEAN | DEFAULT TRUE |
| timestamps + deleted_at | | |

### `orders`
id, order_number (UNIQUE), user_id (FK), status (ENUM
'pending','paid','processing','completed','cancelled'), subtotal, discount,
total (DECIMAL 12,2), invoice_path (VARCHAR NULL), timestamps.

### `order_items`
id, order_id (FK), product_id (FK), quantity (INT UNSIGNED),
unit_price (DECIMAL 12,2), total_price (DECIMAL 12,2).

## 6. Entreprises des membres

### `businesses`
| Colonne | Type | Contraintes |
|---|---|---|
| id | BIGINT UNSIGNED | PK |
| user_id | BIGINT UNSIGNED | FK → users.id (membre propriétaire) |
| name | VARCHAR(200) | NOT NULL |
| slug | VARCHAR(220) | UNIQUE |
| logo_path | VARCHAR(255) | NULL |
| description | TEXT | NULL |
| whatsapp | VARCHAR(30) | NULL |
| facebook_url | VARCHAR(255) | NULL |
| linkedin_url | VARCHAR(255) | NULL |
| website_url | VARCHAR(255) | NULL |
| address | VARCHAR(255) | NULL |
| is_published | BOOLEAN | DEFAULT FALSE (validation admin) |
| timestamps + deleted_at | | |

### `business_services`
id, business_id (FK), name, description, icon, `order` (TINYINT).

### `business_media` (photos/vidéos/portfolio)
id, business_id (FK), type (ENUM 'photo','video','portfolio'), path/url,
caption, `order`, timestamps.

### `business_contact_messages`
id, business_id (FK), name, email, phone, message, is_read (BOOLEAN),
created_at.

## 7. Contenus (actualités, événements, galerie, pages, FAQ, témoignages)

### `articles` (actualités / blog)
id, category (VARCHAR), title, slug (UNIQUE), excerpt, content (LONGTEXT),
cover_path, author_id (FK users), published_at, is_published, seo_title,
seo_description, timestamps + deleted_at.

### `events`
id, title, slug (UNIQUE), description, location, starts_at, ends_at,
cover_path, is_published, timestamps.

### `gallery_albums`
id, title, slug, type (ENUM 'photo','video'), cover_path, timestamps.

### `gallery_items`
id, gallery_album_id (FK), path_or_url, caption, `order`, timestamps.

### `pages` (contenu statique : mentions légales, CGU, historique, vision, mission…)
id, title, slug (UNIQUE), content (LONGTEXT), seo_title, seo_description,
is_published, timestamps.

### `faqs`
id, question, answer, category, `order`, is_published, timestamps.

### `partners`
id, name, logo_path, website_url, `order`, is_published, timestamps.

### `reviews` (témoignages)
id, user_id (FK NULL), name, role_title, photo_path, content, rating
(TINYINT 1-5), is_approved (BOOLEAN), timestamps.

### `banners` / `carousels`
id, title, image_path, link_url, `order`, position (ENUM
'home_hero','home_carousel','shop', ...), starts_at, ends_at, is_active,
timestamps.

## 8. CMS / configuration

### `menus`, `menu_items`
`menus`: id, name, slug. `menu_items`: id, menu_id (FK), label, url,
parent_id (auto-FK NULL), `order`, is_active.

### `settings` (clé/valeur, paramètres généraux + SEO globaux)
id, `key` (UNIQUE), value (LONGTEXT/JSON), `group` (VARCHAR, ex:
'general','seo','payment','social'), timestamps.

## 9. Notifications

### `notifications` (table standard Laravel — morph)
id (UUID), type, notifiable_type, notifiable_id, data (JSON), read_at,
timestamps.

### `notification_logs` (traçabilité multicanal : email/whatsapp/sms/push)
id, user_id (FK), channel (ENUM 'mail','whatsapp','sms','fcm','database'),
subject, status (ENUM 'queued','sent','failed'), error_message, timestamps.

## 10. IA

### `ai_conversations`
id, user_id (FK NULL, visiteur anonyme possible via session_id), session_id
(VARCHAR NULL), channel (ENUM 'assistant','quiz_generation'), timestamps.

### `ai_messages`
id, ai_conversation_id (FK), role (ENUM 'user','assistant','system'),
content (LONGTEXT), tokens_used (INT NULL), created_at.

## 11. Diagramme relationnel simplifié

```
users 1---1 teacher_profiles
users 1---1 student_profiles
users 1---N businesses
users 1---N library_subscriptions --N---1 library_plans
users 1---N quiz_candidates --N---1 quiz_editions
quiz_candidates 1---1 quiz_attempts 1---N quiz_attempt_answers --N---1 quiz_questions
quiz_questions 1---N quiz_question_options
quiz_attempts 1---1 quiz_certificates
users 1---N orders 1---N order_items --N---1 products
users 1---N payments (polymorphique : library_subscriptions | quiz_candidates | orders)
library_documents --N---1 library_categories
products --N---1 product_categories
businesses 1---N business_services / business_media / business_contact_messages
articles / events / gallery_albums / pages / faqs / partners / reviews / banners : contenus indépendants liés à users (auteur) le cas échéant
```

## 12. Notes d'implémentation (migrations)

Précisions apportées lors de la traduction du MCD en migrations Laravel
(`database/migrations/`) :

- `quiz_editions` porte un `slug` (URL publique de l'édition) et
  `questions_per_attempt` (nombre de questions tirées aléatoirement par
  tentative parmi la banque validée du sujet).
- `quiz_attempts` conserve `question_order` (JSON) : la séquence de
  questions tirée pour cette tentative précise, nécessaire pour rejouer
  l'ordre aléatoire côté serveur sans dépendre du client.
- `quiz_attempt_answers` a une contrainte UNIQUE (`quiz_attempt_id`,
  `quiz_question_id`) pour empêcher une double réponse à la même question.
- `users` inclut `first_name`/`last_name` (au lieu d'un `name` unique) pour
  couvrir les besoins des formulaires (candidats quiz, factures) et un
  `soft delete` pour permettre la désactivation de compte réversible.

## 13. Conventions d'index & contraintes

- Toutes les FK ont un index et une politique `ON DELETE` explicite
  (`cascade` pour les enfants exclusifs, `restrict`/`set null` sinon).
- Toutes les colonnes `slug` sont `UNIQUE`.
- Colonnes de recherche fréquente (`status`, `type`, `email`, `created_at`)
  indexées ou en index composite selon les requêtes attendues.
- Montants en `DECIMAL(12,2)` (jamais de `FLOAT`) pour exactitude monétaire.
