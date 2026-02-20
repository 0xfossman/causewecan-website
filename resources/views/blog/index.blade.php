@extends('layouts.app')

@section('content')
    <div class="card hero">
        <span class="badge">Guild Updates</span>
        <h2>Guild News</h2>
        <p class="muted">Latest announcements, progress updates, and scheduling notes.</p>
    </div>

    @forelse ($posts as $post)
        <article class="card">
            <h3>{{ $post->title }}</h3>
            <small class="muted">{{ optional($post->published_at)->format('Y-m-d H:i') }}</small>
            <p>{{ $post->excerpt }}</p>
            <div>{!! nl2br(e($post->content)) !!}</div>
        </article>
    @empty
        <div class="card">No posts yet.</div>
    @endforelse
@endsection
