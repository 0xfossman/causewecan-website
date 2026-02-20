@extends('layouts.app')

@section('content')
<div class="grid">
    <div class="card">
        <h2>Interactive Calendar</h2>
        <p>Click on an entry for details.</p>
        <p class="muted">
            <a href="{{ route('calendar.export.ics') }}">Kalender als .ics herunterladen</a>
            ·
            <a href="{{ str_replace('http://', 'webcal://', str_replace('https://', 'webcal://', route('calendar.export.ics'))) }}">Direkt in Kalender-App abonnieren</a>
        </p>
        <div id="calendar"></div>
    </div>

    @if($canManageCalendar)
        <div class="card">
            <h3>Create Event</h3>
            <form method="post" action="{{ route('calendar.store') }}">
                @csrf
                <label>Title<input name="title" required></label>
                <label>Type<select name="event_type"><option>raid</option><option>dungeon</option><option>meeting</option></select></label>
                <label>Start<input type="datetime-local" name="start_at" required></label>
                <label>End<input type="datetime-local" name="end_at" required></label>
                <label>Description<textarea name="description" rows="4"></textarea></label>
                <button class="btn" type="submit">Save</button>
            </form>
        </div>
    @else
        <div class="card">
            <h3>Calendar permissions</h3>
            <p class="muted">Only members with mapped Discord admin roles can create or edit calendar events.</p>
        </div>
    @endif
</div>

<script>
const events = @json($events);
const calendar = document.getElementById('calendar');
calendar.innerHTML = events.length
 ? '<ul>' + events.map(e => `<li><strong>${new Date(e.start_at).toLocaleString('en-US')}</strong> — ${e.title} (${e.event_type})</li>`).join('') + '</ul>'
 : '<p>No events planned.</p>';
</script>
@endsection
