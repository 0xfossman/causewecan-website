@extends('layouts.app')

@section('content')
<div class="card">
    <h2>WoW Guild Roster</h2>
    <p>Data source: Stormforge Armory/API.</p>
</div>
<div class="card">
    <table>
        <thead>
            <tr><th>Name</th><th>Class</th><th>Spec</th><th>Level</th><th>Rank</th><th>Armory</th></tr>
        </thead>
        <tbody>
        @forelse($members as $member)
            <tr>
                <td>{{ $member['name'] ?? $member->name }}</td>
                <td>{{ $member['class_name'] ?? $member['class'] ?? '-' }}</td>
                <td>{{ $member['spec_name'] ?? $member['spec'] ?? '-' }}</td>
                <td>{{ $member['level'] ?? '-' }}</td>
                <td>{{ $member['rank_name'] ?? $member['rank'] ?? '-' }}</td>
                <td><a href="{{ $member['armory_url'] ?? '#' }}" target="_blank" rel="noreferrer">Profile</a></td>
            </tr>
        @empty
            <tr><td colspan="6">No roster data found. Check Stormforge API key and endpoint configuration.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
