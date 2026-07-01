<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Content\Event;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $upcoming = Event::query()->published()->where('starts_at', '>=', now())->orderBy('starts_at')->get();
        $past = Event::query()->published()->where('starts_at', '<', now())->orderByDesc('starts_at')->paginate(9);

        return view('site.events.index', compact('upcoming', 'past'));
    }

    public function show(Event $event): View
    {
        abort_unless($event->is_published, 404);

        return view('site.events.show', compact('event'));
    }
}
