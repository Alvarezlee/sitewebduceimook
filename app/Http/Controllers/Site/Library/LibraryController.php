<?php

namespace App\Http\Controllers\Site\Library;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryCategory;
use App\Models\Library\LibraryDocument;
use App\Models\Library\LibraryPlan;
use App\Models\Library\LibrarySubscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class LibraryController extends Controller
{
    public function index(): View
    {
        $categories = LibraryCategory::query()->withCount('documents')->orderBy('name')->get();
        $plans = LibraryPlan::query()->where('is_active', true)->orderBy('price')->get();
        $latestDocuments = LibraryDocument::query()->published()->latest()->take(8)->get();

        return view('library.index', [
            'categories' => $categories,
            'plans' => $plans,
            'latestDocuments' => $latestDocuments,
            'activeSubscription' => $this->activeSubscription(),
        ]);
    }

    public function category(LibraryCategory $category): View
    {
        $documents = LibraryDocument::query()
            ->published()
            ->where('library_category_id', $category->id)
            ->latest()
            ->paginate(12);

        return view('library.category', [
            'category' => $category,
            'documents' => $documents,
            'activeSubscription' => $this->activeSubscription(),
        ]);
    }

    public function show(LibraryDocument $document): View
    {
        abort_unless($document->is_published, 404);

        $document->loadMissing('category');

        $canDownload = Auth::check() && ($document->is_free || (bool) $this->activeSubscription()?->hasRemainingDownloads());

        return view('library.show', [
            'document' => $document,
            'activeSubscription' => $this->activeSubscription(),
            'canDownload' => $canDownload,
            'downloadUrl' => $canDownload
                ? URL::temporarySignedRoute('library.download', now()->addMinutes(5), ['document' => $document->id])
                : null,
        ]);
    }

    private function activeSubscription(): ?LibrarySubscription
    {
        if (! Auth::check()) {
            return null;
        }

        return LibrarySubscription::query()
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->where('ends_at', '>=', now())
            ->with('plan')
            ->latest('ends_at')
            ->first();
    }
}
