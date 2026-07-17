@extends('layouts.public')

@section('title', 'Live Match Feed - CRICKTRACKER-KUET')

@section('header_banner')
    <header class="hero-banner">
        <div class="container text-center">
            <h1 class="fw-800 mb-2">Live Match Center</h1>
            <p class="text-white-50 fs-6 mb-0">Track real-time score updates, current overs, and down-the-wire tournament action.</p>
        </div>
    </header>
@endsection

@section('content')
    <!-- Injecting styling for premium card hovering effects -->
    <style>
        .live-card-link {
            text-decoration: none !important;
            color: inherit !important;
            display: block;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .live-card-link:hover {
            transform: translateY(-4px);
        }
        .live-card-link:hover .cric-card {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            border-color: rgba(0, 123, 255, 0.2);
        }
    </style>

    <div class="row">
        <div class="col-12">
            <h2 class="section-title mb-4">Active Live Feeds</h2>
            <div class="row g-4">
                @forelse($liveMatches as $match)
                    <div class="col-12 col-md-6 col-lg-4">
                        <!-- FIX: Pointing to public.matches.show as defined in web.php -->
                        <a href="{{ route('public.matches.show', $match->id) }}" class="live-card-link">
                            <div class="cric-card p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-danger text-white px-2 py-1 uppercase small">
                                        <i class="fa-solid fa-circle small me-1"></i> LIVE NOW
                                    </span>
                                    <small class="text-muted">
                                        <i class="fa-solid fa-location-dot me-1"></i> {{ $match->venue }}
                                    </small>
                                </div>
                                <div class="d-flex flex-column gap-2 my-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-dark fs-5">{{ $match->teamOne->name }}</span>
                                        <span class="fw-bold fs-5 text-dark">
                                            {{ $match->matchScore->runs ?? 0 }}/{{ $match->matchScore->wickets ?? 0 }}
                                            <small class="text-muted fw-normal fs-7">
                                                ({{ floor(($match->matchScore->balls_bowled ?? 0) / 6) }}.{{ ($match->matchScore->balls_bowled ?? 0) % 6 }} ov)
                                            </small>
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted fs-5 fw-medium">{{ $match->teamTwo->name }}</span>
                                        <span class="fs-7 text-muted fw-medium">Yet to bat</span>
                                    </div>
                                </div>
                                <div class="border-top pt-3 mt-2">
                                    <small class="text-danger fw-semibold">
                                        <i class="fa-solid fa-clock me-1"></i> Innings 1 coverage is active.
                                    </small>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="bg-white rounded-3 border p-5 text-center text-muted shadow-xs">
                            <i class="fa-solid fa-satellite-dish fs-1 text-muted mb-3 d-block"></i>
                            No active local match feeds are live right now. Active coverage dashboards appear automatically.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection