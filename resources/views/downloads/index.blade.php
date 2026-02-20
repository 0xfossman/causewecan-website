@extends('layouts.app')

@section('content')
<div class="card hero">
    <span class="badge">Discord Files</span>
    <h2>Downloads</h2>
    <p class="muted">Files are sourced from a configured Discord channel and linked here for quick access.</p>
</div>

<div class="card">
    <table>
        <thead>
            <tr><th>File</th><th>Type</th><th>Size</th><th>Uploaded</th><th>Download</th></tr>
        </thead>
        <tbody>
        @forelse($files as $file)
            <tr>
                <td>{{ $file['filename'] ?? 'file' }}</td>
                <td>{{ $file['content_type'] ?? 'unknown' }}</td>
                <td>{{ number_format(($file['size'] ?? 0) / 1024, 2) }} KB</td>
                <td>{{ isset($file['uploaded_at']) ? \Carbon\Carbon::parse($file['uploaded_at'])->format('Y-m-d H:i') : '-' }}</td>
                <td><a href="{{ $file['url'] ?? '#' }}" target="_blank" rel="noreferrer">Open</a></td>
            </tr>
        @empty
            <tr><td colspan="5">No files found. Check Discord bot API key and downloads channel ID.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
