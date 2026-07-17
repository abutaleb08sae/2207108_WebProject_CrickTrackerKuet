@extends('layouts.public')

@section('title', 'Recent Match Results - CRICKTRACKER-KUET')

@push('styles')
<style>
    .match-link-wrapper {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .match-link-wrapper:hover {
        transform: translateY(-4px);
    }
    .match-link-wrapper:hover .cric-card {
        box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.12)!important;
    }
</style>
@endpush

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
    <div class="row g-4 text-start">
        @forelse($completedMatches as $match)
            @php
                $s = $match->matchScore;
                $t1Active = $s && $s->innings_one_batting_team_id == $match->team_one_id;
            @endphp
            <div class="col-12 col-md-6">
                <!-- FIXED: Changed route name from public.live-board to public.matches.show -->
                <a href="{{ route('public.matches.show', $match->id) }}" class="text-decoration-none d-block h-100 match-link-wrapper">
                    <div class="cric-card p-4 bg-white rounded shadow-sm border h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                            <span class="badge bg-secondary text-white small px-2 py-1 rounded">MATCH CONCLUDED</span>
                            <small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i> {{ $match->venue ?? 'KUET Ground' }}</small>
                        </div>
                        
                        <!-- 1st Innings Display Box -->
                        <div class="d-flex justify-content-between align-items-center my-2">
                            <span class="fs-5 {{ $match->winner_id == ($t1Active ? $match->team_one_id : $match->team_two_id) ? 'fw-bold text-dark' : 'text-muted' }}">
                                @if($match->winner_id == ($t1Active ? $match->team_one_id : $match->team_two_id)) 
                                    <i class="fa-solid fa-circle-check text-success me-1"></i> 
                                @endif
                                {{ $t1Active ? $match->teamOne?->name : $match->teamTwo?->name }}
                            </span>
                            <span class="fw-bold fs-5 text-dark">
                                {{ $s->innings_one_runs ?? 0 }}/{{ $s->innings_one_wickets ?? 0 }}
                                <small class="text-muted fs-7">({{ floor(($s->innings_one_balls ?? 0) / 6) }}.{{ ($s->innings_one_balls ?? 0) % 6 }} Ov)</small>
                            </span>
                        </div>
                        
                        <!-- 2nd Innings Display Box -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fs-5 {{ $match->winner_id == ($t1Active ? $match->team_two_id : $match->team_one_id) ? 'fw-bold text-dark' : 'text-muted' }}">
                                @if($match->winner_id == ($t1Active ? $match->team_two_id : $match->team_one_id)) 
                                    <i class="fa-solid fa-circle-check text-success me-1"></i> 
                                @endif
                                {{ $t1Active ? $match->teamTwo?->name : $match->teamOne?->name }}
                            </span>
                            <span class="fw-bold fs-5 text-dark">
                                {{ $s->innings_two_runs ?? 0 }}/{{ $s->innings_two_wickets ?? 0 }}
                                <small class="text-muted fs-7">({{ floor(($s->innings_two_balls ?? 0) / 6) }}.{{ ($s->innings_two_balls ?? 0) % 6 }} Ov)</small>
                            </span>
                        </div>
                        
                        <div class="border-top pt-3 mt-2 text-center text-success small fw-bold bg-light p-2 rounded">
                            <i class="fa-solid fa-trophy text-warning me-1"></i> 
                            {{ $s->match_result_string ?? 'Match Completed' }}
                        </div>
                    </div>
                </a>
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