<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\ContactRequest;
use App\Mail\ContactMessageReceived;
use App\Models\Cms\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('site.contact');
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        $adminEmail = Setting::get('contact.recipient_email', config('mail.from.address'));

        Mail::to($adminEmail)->queue(new ContactMessageReceived($request->validated()));

        return back()->with('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les meilleurs délais.');
    }
}
