<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Permissions disponibles, regroupées par module.
     *
     * @var array<string, list<string>>
     */
    private const PERMISSIONS = [
        'users' => ['users.view', 'users.manage', 'roles.manage'],
        'content' => ['content.manage', 'gallery.manage', 'faq.manage', 'partners.manage', 'reviews.moderate', 'banners.manage', 'menus.manage'],
        'library' => ['library.categories.manage', 'library.plans.manage', 'library.documents.manage', 'library.subscriptions.view'],
        'quiz' => ['quiz.subjects.manage', 'quiz.editions.manage', 'quiz.questions.create', 'quiz.questions.validate', 'quiz.attempts.grade', 'quiz.results.view'],
        'shop' => ['shop.categories.manage', 'shop.products.manage', 'shop.orders.view', 'shop.orders.manage'],
        'business' => ['business.profile.edit', 'business.validate'],
        'payments' => ['payments.view', 'payments.manage'],
        'settings' => ['settings.manage', 'seo.manage', 'backups.manage', 'logs.view'],
    ];

    public function run(): void
    {
        Cache::forget('spatie.permission.cache');

        $allPermissions = collect(self::PERMISSIONS)->flatten();

        $allPermissions->each(fn (string $name) => Permission::query()->firstOrCreate(['name' => $name]));

        $superAdmin = Role::query()->firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions($allPermissions);

        $admin = Role::query()->firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($allPermissions->reject(fn (string $name) => $name === 'roles.manage'));

        $moderateur = Role::query()->firstOrCreate(['name' => 'moderateur']);
        $moderateur->syncPermissions([
            ...self::PERMISSIONS['content'],
            'reviews.moderate',
            'business.validate',
            'library.documents.manage',
        ]);

        $enseignant = Role::query()->firstOrCreate(['name' => 'enseignant']);
        $enseignant->syncPermissions([
            'quiz.questions.create',
            'quiz.attempts.grade',
            'quiz.results.view',
        ]);

        $eleve = Role::query()->firstOrCreate(['name' => 'eleve']);
        $eleve->syncPermissions([]);

        $entreprise = Role::query()->firstOrCreate(['name' => 'entreprise']);
        $entreprise->syncPermissions([
            'business.profile.edit',
        ]);

        Role::query()->firstOrCreate(['name' => 'partenaire']);
    }
}
