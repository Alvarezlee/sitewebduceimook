<?php

namespace App\Http\Controllers\Admin\Business;

use App\Http\Controllers\Controller;
use App\Models\Business\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BusinessController extends Controller
{
    public function index(): View
    {
        $businesses = Business::query()->with('user')->latest()->paginate(20);

        return view('admin.businesses.index', compact('businesses'));
    }

    public function togglePublish(Business $business): RedirectResponse
    {
        $business->update(['is_published' => ! $business->is_published]);

        return back()->with('success', 'Statut de publication mis à jour.');
    }

    public function destroy(Business $business): RedirectResponse
    {
        $business->delete();

        return redirect()->route('admin.businesses.index')->with('success', 'Entreprise supprimée.');
    }
}
