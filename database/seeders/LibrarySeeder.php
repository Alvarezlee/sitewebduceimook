<?php

namespace Database\Seeders;

use App\Models\Library\LibraryCategory;
use App\Models\Library\LibraryDocument;
use App\Models\Library\LibraryPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LibrarySeeder extends Seeder
{
    public function run(): void
    {
        LibraryPlan::query()->firstOrCreate(
            ['name' => 'Mensuel'],
            ['price' => 1000, 'duration_days' => 30, 'max_downloads' => 10, 'description' => 'Accès 30 jours, 10 téléchargements.', 'is_active' => true],
        );

        LibraryPlan::query()->firstOrCreate(
            ['name' => 'Trimestriel'],
            ['price' => 2500, 'duration_days' => 90, 'max_downloads' => 40, 'description' => 'Accès 90 jours, 40 téléchargements.', 'is_active' => true],
        );

        LibraryPlan::query()->firstOrCreate(
            ['name' => 'Annuel'],
            ['price' => 8000, 'duration_days' => 365, 'max_downloads' => null, 'description' => 'Accès 1 an, téléchargements illimités.', 'is_active' => true],
        );

        $categoryNames = [
            'Probatoires', 'Baccalauréat', 'BEPC', 'CAP', 'BTS', 'Concours',
            'Corrigés', 'Fascicules', 'Livres',
        ];

        foreach ($categoryNames as $name) {
            $category = LibraryCategory::query()->firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'icon' => 'book-open'],
            );

            LibraryDocument::factory(5)->create([
                'library_category_id' => $category->id,
            ]);
        }
    }
}
