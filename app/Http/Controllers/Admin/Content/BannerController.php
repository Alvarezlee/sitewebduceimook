<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(): View
    {
        $banners = Banner::query()->orderBy('order')->paginate(20);

        return view('admin.content.banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.content.banners.form', ['banner' => new Banner]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['image_path'] = $request->file('image')->store('banners', 'local');

        Banner::query()->create($validated);

        return redirect()->route('admin.content.banners.index')->with('success', 'Bannière créée.');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.content.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $validated = $this->validated($request, false);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('banners', 'local');
        }

        $banner->update($validated);

        return redirect()->route('admin.content.banners.index')->with('success', 'Bannière mise à jour.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $banner->delete();

        return redirect()->route('admin.content.banners.index')->with('success', 'Bannière supprimée.');
    }

    private function validated(Request $request, bool $imageRequired = true): array
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:200'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'position' => ['required', 'in:home_hero,home_carousel,shop'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'max:4096'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['order'] = $validated['order'] ?? 0;
        unset($validated['image']);

        return $validated;
    }
}
