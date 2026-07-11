@extends('layouts.public')

@section('title', 'Upcoming Schedules - CRICKTRACKER-KUET')

@section('header_banner')
    <header class="hero-banner">
        <div class="container text-center">
            <h1 class="fw-800 mb-2">Tournament Schedules</h1>
            <p class="text-white-50 fs-6 mb-0">Plan ahead with absolute schedules for inter-department group configurations.</p>
        </div>
    </header>
@endsection

@section('content')
    <h2 class="section-title mb-4">Upcoming Fixture Lineups</h2>
    <div class="row g-4">
        @forelse($upcomingMatches as $match)
            <div class="col-12 col-md-6">
                <div class="cric-card p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-info text-dark mb-2 d-inline-block px-2 py-1 rounded small">Match Fixture</span>
                        <div class="fw-bold fs-5 text-dark mb-1">{{ $match->teamOne->name }}</div>
                        <div class="text-muted fs-5 fw-medium">{{ $match->teamTwo->name }}</div>
                    </div>
                    <div class="text-end border-start ps-4" style="min-width: 150px;">
                        <div class="fw-bold text-dark"><i class="fa-regular fa-clock me-1 text-primary"></i> {{ date('h:i A', strtotime($match->match_datetime)) }}</div>
                        <div class="text-muted small mt-1">{{ date('M d, Y', strtotime($match->match_datetime)) }}</div>
                        <div class="text-secondary small mt-1 text-truncate" style="max-width:140px;"><i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $match->venue }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="bg-white rounded-3 border p-5 text-center text-muted">
                    No future tournament matches currently mapped out to structural timelines.
                </div>
            </div>
        @endforelse
    </div>
@endsection