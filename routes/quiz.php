<?php

use App\Http\Controllers\Site\Quiz\AttemptController;
use App\Http\Controllers\Site\Quiz\CertificateDownloadController;
use App\Http\Controllers\Site\Quiz\LeaderboardController;
use App\Http\Controllers\Site\Quiz\QuizController;
use App\Http\Controllers\Site\Quiz\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/moungo-tic-quizz', [QuizController::class, 'index'])->name('quiz.landing');
Route::get('/moungo-tic-quizz/classement/{edition}', LeaderboardController::class)->name('quiz.leaderboard');

Route::middleware('auth')->group(function () {
    Route::get('/moungo-tic-quizz/{edition}/inscription', [RegistrationController::class, 'create'])->name('quiz.register');
    Route::post('/moungo-tic-quizz/{edition}/inscription', [RegistrationController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('quiz.register.store');

    Route::get('/moungo-tic-quizz/{edition}/demarrer', [AttemptController::class, 'start'])->name('quiz.attempt.start');
    Route::get('/moungo-tic-quizz/{edition}/tentative', [AttemptController::class, 'show'])->name('quiz.attempt.show');
    Route::get('/moungo-tic-quizz/{edition}/resultat', [AttemptController::class, 'result'])->name('quiz.attempt.result');

    Route::get('/moungo-tic-quizz/certificat/{certificate}', CertificateDownloadController::class)
        ->middleware('signed')
        ->name('quiz.certificate.download');
});
