@extends('layouts.app')

@section('content')
    <div class="card"><h2>Guild News</h2><p>Latest blog posts on the homepage.</p></div>
    @forelse ($posts as $post)
        <article class="card">
            <h3>{{ $post->title }}</h3>
            <small>{{ optional($post->published_at)->format('Y-m-d H:i') }}</small>
            <p>{{ $post->excerpt }}</p>
            <div>{!! nl2br(e($post->content)) !!}</div>
        </article>
    @empty
        <div class="card">No posts yet.</div>
    @endforelse
@endsection
