<?php

use App\Http\Controllers\Teacher\QuestionController;
use Illuminate\Support\Facades\Route;

Route::prefix('enseignant')
    ->name('teacher.')
    ->middleware(['auth', 'verified', 'role:enseignant|admin|super_admin'])
    ->group(function (): void {
        Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
        Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
        Route::post('/questions/generer-ia', [QuestionController::class, 'generate'])->name('questions.generate');
        Route::post('/questions/{question}/valider', [QuestionController::class, 'validateQuestion'])->name('questions.validate');
        Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    });
