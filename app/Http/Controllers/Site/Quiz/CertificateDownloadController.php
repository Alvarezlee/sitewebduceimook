<?php

namespace App\Http\Controllers\Site\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\QuizCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CertificateDownloadController extends Controller
{
    public function __invoke(Request $request, QuizCertificate $certificate): StreamedResponse
    {
        abort_unless($request->hasValidSignature(), 403);

        $certificate->loadMissing('attempt.candidate');
        abort_unless($certificate->attempt->candidate->user_id === $request->user()?->id, 403);

        return Storage::disk('local')->download($certificate->file_path, $certificate->certificate_number.'.pdf');
    }
}
