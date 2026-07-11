@extends('layouts.public')

@section('title', 'Recent Match Results - CRICKTRACKER-KUET')

@section('header_banner')
    <header class="hero-banner">
        <div class="container text-center">
            <h1 class="fw-800 mb-2">Concluded Results</h1>
            <p class="text-white-50 fs-6 mb-0">Review archived score sheets, total records, and definitive match outcomes.</p>
        </div>
    </header>
@endsection

@section('content')
    <h2 class="section-title mb-4">Match Histories</h2>
    <div class="row g-4">
        @forelse($completedMatches as $match)
            <div class="col-12 col-md-6 text-start">
                <div class="cric-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-secondary text-white small px-2 py-1 rounded">MATCH CONCLUDED</span>
                        <small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i> {{ $match->venue }}</small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <span class="fs-5 fw-bold text-success"><i class="fa-solid fa-circle-check me-1"></i> {{ $match->teamOne->name }}</span>
                        <span class="fw-bold fs-5 text-dark">{{ $match->matchScore->runs ?? 0 }}/{{ $match->matchScore->wickets ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fs-5 text-muted">{{ $match->teamTwo->name }}</span>
                        <span class="fs-7 text-muted">Target Chased</span>
                    </div>
                    <div class="border-top pt-3 mt-3 text-center text-success small fw-bold">
                        Match Completed • Total Overs: {{ floor(($match->matchScore->balls_bowled ?? 0) / 6) }}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="bg-white rounded-3 border p-5 text-center text-muted">
                    No match results recorded inside the system tracking logs yet.
                </div>
            </div>
        @endforelse
    </div>
@endsection