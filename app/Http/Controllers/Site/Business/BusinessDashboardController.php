<?php

namespace App\Http\Controllers\Site\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\Business\UpdateBusinessRequest;
use App\Models\Business\Business;
use App\Models\Business\BusinessMedia;
use App\Models\Business\BusinessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BusinessDashboardController extends Controller
{
    public function edit(): View
    {
        $business = $this->ownedBusiness();

        return view('business.dashboard', compact('business'));
    }

    public function update(UpdateBusinessRequest $request): RedirectResponse
    {
        $business = $this->ownedBusiness();

        $data = $request->safe()->except('logo');

        if ($business === null) {
            $business = Business::query()->create([
                ...$data,
                'user_id' => Auth::id(),
                'slug' => Str::slug($data['name']).'-'.Str::random(6),
                'is_published' => false,
            ]);
        } else {
            $this->authorize('manage', $business);
            $business->update($data);
        }

        if ($request->hasFile('logo')) {
            $business->update(['logo_path' => $request->file('logo')->store('business/logos', 'local')]);
        }

        return redirect()->route('business.dashboard.edit')
            ->with('success', 'Votre vitrine a été mise à jour. Elle sera visible après validation par un administrateur.');
    }

    public function storeService(Request $request): RedirectResponse
    {
        $business = $this->ownedBusinessOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $business->services()->create($validated);

        return redirect()->route('business.dashboard.edit')->with('success', 'Service ajouté.');
    }

    public function destroyService(BusinessService $service): RedirectResponse
    {
        $this->authorize('manage', $service->business);
        $service->delete();

        return redirect()->route('business.dashboard.edit')->with('success', 'Service supprimé.');
    }

    public function storeMedia(Request $request): RedirectResponse
    {
        $business = $this->ownedBusinessOrFail();

        $validated = $request->validate([
            'type' => ['required', 'in:photo,video,portfolio'],
            'file' => ['required', 'file', 'max:10240'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $path = $request->file('file')->store('business/media', 'local');

        $business->media()->create([
            'type' => $validated['type'],
            'path_or_url' => $path,
            'caption' => $validated['caption'] ?? null,
        ]);

        return redirect()->route('business.dashboard.edit')->with('success', 'Média ajouté.');
    }

    public function destroyMedia(BusinessMedia $media): RedirectResponse
    {
        $this->authorize('manage', $media->business);
        $media->delete();

        return redirect()->route('business.dashboard.edit')->with('success', 'Média supprimé.');
    }

    private function ownedBusiness(): ?Business
    {
        return Business::query()->where('user_id', Auth::id())->first();
    }

    private function ownedBusinessOrFail(): Business
    {
        return Business::query()->where('user_id', Auth::id())->firstOrFail();
    }
}
