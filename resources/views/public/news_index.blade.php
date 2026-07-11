@extends('layouts.public')

@section('title', 'Sports Notices & Bulletins - CRICKTRACKER-KUET')

@section('header_banner')
    <header class="hero-banner">
        <div class="container text-center">
            <h1 class="fw-800 mb-2">Sports Notices & Bulletins</h1>
            <p class="text-white-50 fs-6 mb-0">Stay updated with official match announcements, schedule changes, and tournament news.</p>
        </div>
    </header>
@endsection

@section('content')
    <h2 class="section-title mb-4">Official Announcements</h2>
    <div class="row g-4">
        <div class="col-12">
            <div class="d-flex flex-column gap-3">
                @forelse($allNews as $news)
                    <div class="cric-card p-4 text-start">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-light text-dark border fs-7 rounded-2 px-2 py-1 fw-bold text-uppercase">{{ $news->category ?? 'Notice' }}</span>
                            <small class="text-muted"><i class="fa-regular fa-calendar me-1"></i> {{ $news->created_at ? $news->created_at->diffForHumans() : 'Recently' }}</small>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">{{ $news->title }}</h4>
                        <p class="text-muted mb-0 fs-6" style="white-space: pre-line;">
                            {{ $news->content }}
                        </p>
                    </div>
                @empty
                    <div class="bg-white rounded-3 border p-5 text-center text-muted shadow-xs">
                        <i class="fa-solid fa-newspaper fs-1 text-muted mb-3 d-block"></i>
                        No announcement listings published yet. Check back later for campus sports news!
                    </div>
                @endforelse
            </div>

            @if(method_exists($allNews, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $allNews->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection