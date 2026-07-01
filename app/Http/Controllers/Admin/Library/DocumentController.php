<?php

namespace App\Http\Controllers\Admin\Library;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryCategory;
use App\Models\Library\LibraryDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(): View
    {
        $documents = LibraryDocument::query()->with('category')->latest()->paginate(20);

        return view('admin.library.documents.index', compact('documents'));
    }

    public function create(): View
    {
        $categories = LibraryCategory::query()->orderBy('name')->get();

        return view('admin.library.documents.form', ['document' => new LibraryDocument, 'categories' => $categories]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = Str::slug($validated['title']).'-'.Str::random(6);
        $validated['file_path'] = $request->file('file')->store('library/documents', 'local');

        LibraryDocument::query()->create($validated);

        return redirect()->route('admin.library.documents.index')->with('success', 'Document ajouté.');
    }

    public function edit(LibraryDocument $document): View
    {
        $categories = LibraryCategory::query()->orderBy('name')->get();

        return view('admin.library.documents.form', compact('document', 'categories'));
    }

    public function update(Request $request, LibraryDocument $document): RedirectResponse
    {
        $validated = $this->validated($request, false);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('library/documents', 'local');
        }

        $document->update($validated);

        return redirect()->route('admin.library.documents.index')->with('success', 'Document mis à jour.');
    }

    public function destroy(LibraryDocument $document): RedirectResponse
    {
        $document->delete();

        return redirect()->route('admin.library.documents.index')->with('success', 'Document supprimé.');
    }

    private function validated(Request $request, bool $fileRequired = true): array
    {
        $validated = $request->validate([
            'library_category_id' => ['required', 'exists:library_categories,id'],
            'title' => ['required', 'string', 'max:200'],
            'type' => ['required', 'in:probatoire,baccalaureat,bepc,cap,bts,concours,corrige,fascicule,livre'],
            'description' => ['nullable', 'string', 'max:2000'],
            'year' => ['nullable', 'integer'],
            'is_free' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
            'file' => [$fileRequired ? 'required' : 'nullable', 'file', 'mimes:pdf', 'max:20480'],
        ]);

        $validated['is_free'] = $request->boolean('is_free');
        $validated['is_published'] = $request->boolean('is_published');
        unset($validated['file']);

        return $validated;
    }
}
