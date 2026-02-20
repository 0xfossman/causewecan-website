<?php

namespace App\Http\Controllers;

use App\Models\GuildEvent;
use App\Support\CalendarPermissionResolver;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

    public function exportIcs(Request $request): Response
    {
        $events = GuildEvent::query()->orderBy('start_at')->get();
        $generatedAt = Carbon::now('UTC')->format('Ymd\\THis\\Z');

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Cause We Can//Guild Calendar//DE',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'X-WR-CALNAME:Cause We Can - Kalender',
        ];

        foreach ($events as $event) {
            $startAt = Carbon::parse($event->start_at)->utc()->format('Ymd\\THis\\Z');
            $endAt = Carbon::parse($event->end_at)->utc()->format('Ymd\\THis\\Z');

            $lines[] = 'BEGIN:VEVENT';
            $lines[] = sprintf('UID:guild-event-%d@causewecan.app', $event->id);
            $lines[] = 'DTSTAMP:'.$generatedAt;
            $lines[] = 'DTSTART:'.$startAt;
            $lines[] = 'DTEND:'.$endAt;
            $lines[] = 'SUMMARY:'.$this->escapeIcsText($event->title);
            $lines[] = 'DESCRIPTION:'.$this->escapeIcsText((string) ($event->description ?? ''));
            $lines[] = 'CATEGORIES:'.$this->escapeIcsText((string) $event->event_type);
            $lines[] = 'END:VEVENT';
        }

        $lines[] = 'END:VCALENDAR';

        return response(implode("\r\n", $lines)."\r\n", 200, [
            'Content-Type' => 'text/calendar; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="causewecan-kalender.ics"',
        ]);
    }

    private function escapeIcsText(string $value): string
    {
        return str_replace(
            ["\\", ';', ',', "\r\n", "\n", "\r"],
            ['\\\\', '\\;', '\\,', '\\n', '\\n', '\\n'],
            trim($value)
        );
    }
}
