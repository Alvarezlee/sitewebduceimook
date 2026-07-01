<?php

namespace App\Http\Controllers\Site\Library;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryDocument;
use App\Services\Library\LibraryDownloadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function __invoke(Request $request, LibraryDocument $document, LibraryDownloadService $downloads): StreamedResponse
    {
        abort_unless($request->hasValidSignature(), 403);
        abort_unless($document->is_published, 404);

        try {
            $downloads->authorizeAndLog($request->user(), $document, (string) $request->ip());
        } catch (RuntimeException $e) {
            abort(403, $e->getMessage());
        }

        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        return Storage::disk('local')->download($document->file_path, $document->title.'.pdf');
    }
}
