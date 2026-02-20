<?php

namespace App\Http\Controllers;

use App\Models\GuildEvent;
use App\Support\CalendarPermissionResolver;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function __construct(private readonly CalendarPermissionResolver $permissionResolver)
    {
    }

    public function index(Request $request): View
    {
        return view('calendar.index', [
            'events' => GuildEvent::query()->orderBy('start_at')->get(),
            'canManageCalendar' => $this->permissionResolver->canManage($request->user()),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after_or_equal:start_at'],
            'event_type' => ['required', 'string', 'max:50'],
        ]);

        GuildEvent::query()->create($data + ['created_by' => $request->user()->id]);

        return redirect()->route('calendar.index')->with('status', 'Event created.');
    }
}
