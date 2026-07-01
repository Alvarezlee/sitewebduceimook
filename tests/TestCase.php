<?php

namespace Tests;

use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Seeder exécuté après chaque "migrate:fresh" (RefreshDatabase) afin que
     * les rôles/permissions RBAC existent pour les tests d'authentification.
     */
    protected $seeder = RolePermissionSeeder::class;
}
