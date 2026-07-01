<?php

namespace Database\Seeders;

use App\Models\Quiz\QuizEdition;
use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\QuizSubject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $subjectNames = ['Informatique', 'TIC', 'Algorithmique', 'Réseaux', 'Programmation', 'Culture numérique'];

        foreach ($subjectNames as $name) {
            $subject = QuizSubject::query()->firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => "Questions sur : {$name}"],
            );

            QuizQuestion::factory(15)->create([
                'quiz_subject_id' => $subject->id,
            ]);
        }

        QuizEdition::query()->firstOrCreate(
            ['slug' => 'moungo-tic-quizz-'.now()->year],
            [
                'name' => 'MOUNGO TIC QUIZZ '.now()->year,
                'registration_price' => 2000,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addMonths(2),
                'duration_minutes' => 30,
                'max_attempts' => 1,
                'questions_per_attempt' => 20,
                'is_active' => true,
            ],
        );
    }
}
