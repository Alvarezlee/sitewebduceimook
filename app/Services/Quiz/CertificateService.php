<?php

namespace App\Services\Quiz;

use App\Models\Quiz\QuizAttempt;
use App\Models\Quiz\QuizCertificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateService
{
    private const PASS_THRESHOLD = 50.0;

    public function isEligible(QuizAttempt $attempt): bool
    {
        return $attempt->status === 'graded' && (float) $attempt->score >= self::PASS_THRESHOLD;
    }

    public function issue(QuizAttempt $attempt): ?QuizCertificate
    {
        if (! $this->isEligible($attempt)) {
            return null;
        }

        if ($attempt->certificate()->exists()) {
            return $attempt->certificate;
        }

        $attempt->loadMissing('candidate.user', 'candidate.edition');

        $certificateNumber = 'CEIMO-'.now()->format('Y').'-'.strtoupper(Str::random(8));

        $pdf = Pdf::loadView('quiz.certificate', [
            'attempt' => $attempt,
            'certificateNumber' => $certificateNumber,
        ]);

        $path = "certificates/{$certificateNumber}.pdf";
        Storage::disk('local')->put($path, $pdf->output());

        return QuizCertificate::query()->create([
            'quiz_attempt_id' => $attempt->id,
            'certificate_number' => $certificateNumber,
            'file_path' => $path,
            'issued_at' => now(),
        ]);
    }
}
