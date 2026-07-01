<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function index(): View
    {
        $partners = Partner::query()->orderBy('order')->paginate(20);

        return view('admin.content.partners.index', compact('partners'));
    }

    public function create(): View
    {
        return view('admin.content.partners.form', ['partner' => new Partner]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['logo_path'] = $request->file('logo')->store('partners', 'local');

        Partner::query()->create($validated);

        return redirect()->route('admin.content.partners.index')->with('success', 'Partenaire ajouté.');
    }

    public function edit(Partner $partner): View
    {
        return view('admin.content.partners.form', compact('partner'));
    }

    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $validated = $this->validated($request, false);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('partners', 'local');
        }

        $partner->update($validated);

        return redirect()->route('admin.content.partners.index')->with('success', 'Partenaire mis à jour.');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        $partner->delete();

        return redirect()->route('admin.content.partners.index')->with('success', 'Partenaire supprimé.');
    }

    private function validated(Request $request, bool $logoRequired = true): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'order' => ['nullable', 'integer'],
            'is_published' => ['sometimes', 'boolean'],
            'logo' => [$logoRequired ? 'required' : 'nullable', 'image', 'max:2048'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');
        $validated['order'] = $validated['order'] ?? 0;
        unset($validated['logo']);

        return $validated;
    }
}
