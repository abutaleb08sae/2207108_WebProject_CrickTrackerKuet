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
    <div class="row g-4 text-start">
        @forelse($completedMatches as $match)
            @php
                $s = $match->matchScore;
                $t1Active = $s && $s->innings_one_batting_team_id == $match->team_one_id;
            @endphp
            <div class="col-12 col-md-6">
                <div class="cric-card p-4 bg-white rounded shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <span class="badge bg-secondary text-white small px-2 py-1 rounded">MATCH CONCLUDED</span>
                        <small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i> {{ $match->venue ?? 'KUET Ground' }}</small>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <span class="fs-5 {{ $match->winner_id == ($t1Active ? $match->team_one_id : $match->team_two_id) ? 'fw-bold text-dark' : 'text-muted' }}">
                            @if($match->winner_id == ($t1Active ? $match->team_one_id : $match->team_two_id)) <i class="fa-solid fa-circle-check text-success me-1"></i> @endif
                            {{ $t1Active ? $match->teamOne->name : $match->teamTwo->name }}
                        </span>
                        <span class="fw-bold fs-5 text-dark">
                            {{ $s->innings_one_runs ?? 0 }}/{{ $s->innings_one_wickets ?? 0 }}
                            <small class="text-muted fs-7">({{ floor(($s->innings_one_balls ?? 0) / 6) }}.{{ ($s->innings_one_balls ?? 0) % 6 }} Ov)</small>
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fs-5 {{ $match->winner_id == ($t1Active ? $match->team_two_id : $match->team_one_id) ? 'fw-bold text-dark' : 'text-muted' }}">
                            @if($match->winner_id == ($t1Active ? $match->team_two_id : $match->team_one_id)) <i class="fa-solid fa-circle-check text-success me-1"></i> @endif
                            {{ $t1Active ? $match->teamTwo->name : $match->teamOne->name }}
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