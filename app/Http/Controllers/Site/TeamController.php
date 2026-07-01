<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\TeacherProfile;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function bureau(): View
    {
        $members = TeacherProfile::query()
            ->with('user')
            ->where('is_bureau_member', true)
            ->orderBy('bureau_order')
            ->get();

        return view('site.team-bureau', compact('members'));
    }

    public function members(): View
    {
        $teachers = TeacherProfile::query()
            ->with('user')
            ->where('is_bureau_member', false)
            ->paginate(24);

        return view('site.team-members', compact('teachers'));
    }
}
