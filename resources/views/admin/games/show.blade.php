@extends('layouts.admin')

@section('admin-content')
<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Match Card Dashboard</h1>
</div>

<div class="mb-4 d-flex justify-content-between align-items-center">
    <a href="{{ route('games.index') }}" class="text-decoration-none fw-bold text-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Match Fixtures
    </a>
    <div>
        <a href="{{ route('games.edit', $game->id) }}" class="btn btn-outline-secondary btn-sm me-2">
            <i class="fa-solid fa-pen-to-square me-1"></i> Update Scorecard
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Main Scorecard Core Display Card -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <div>
                        <span class="badge bg-dark font-monospace text-uppercase py-1.5 px-2.5 fs-8">{{ $game->match_number }}</span>
                        <span class="text-secondary small ms-2 fw-medium">
                            <i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $game->venue }}
                        </span>
                    </div>
                    <div>
                        <span class="badge rounded-pill px-3 py-2 fs-8
                            @if($game->status === 'Scheduled') bg-secondary
                            @elseif($game->status === 'Live') bg-danger
                            @elseif($game->status === 'Completed') bg-success
                            @else bg-dark @endif">
                            {{ $game->status }}
                        </span>
                    </div>
                </div>

                <!-- Head-to-Head Versus Layout -->
                <div class="row align-items-center justify-content-center my-4 py-2 text-center">
                    <!-- Team 1 Details -->
                    <div class="col-5">
                        <h4 class="fw-bold text-dark mb-2">{{ $game->team1->name }}</h4>
                        <div class="h2 fw-bold text-primary font-monospace mb-0">
                            {{ $game->team1_score ?? '—' }}
                        </div>
                        <small class="text-muted text-uppercase fw-bold tracking-wider fs-9">Team A / First Innings</small>
                    </div>

                    <!-- Versus Icon Separator -->
                    <div class="col-2 text-center">
                        <div class="bg-light border rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width: 45px; height: 45px;">
                            <span class="fw-bold text-secondary small">VS</span>
                        </div>
                    </div>

                    <!-- Team 2 Details -->
                    <div class="col-5">
                        <h4 class="fw-bold text-dark mb-2">{{ $game->team2->name }}</h4>
                        <div class="h2 fw-bold text-primary font-monospace mb-0">
                            {{ $game->team2_score ?? '—' }}
                        </div>
                        <small class="text-muted text-uppercase fw-bold tracking-wider fs-9">Team B / Second Innings</small>
                    </div>
                </div>

                <!-- Match Verdict Summary Alert Box -->
                @if($game->result_description)
                    <div class="alert alert-warning border-0 shadow-sm text-center fw-bold mb-0 mt-4 text-dark p-3">
                        <i class="fa-solid fa-circle-check text-success me-2 fs-5 align-middle"></i>
                        {{ $game->result_description }}
                    </div>
                @elseif($game->status === 'Completed' && !$game->result_description && $game->winner)
                    <div class="alert alert-success border-0 shadow-sm text-center fw-bold mb-0 mt-4 text-dark p-3">
                        <i class="fa-solid fa-trophy text-warning me-2 fs-5 align-middle"></i>
                        Match concluded. Winner: {{ $game->winner->name }}
                    </div>
                @elseif($game->status === 'Live')
                    <div class="alert alert-danger border-0 shadow-sm text-center fw-bold mb-0 mt-4 text-dark p-3 animate-pulse">
                        <i class="fa-solid fa-tower-broadcast text-danger me-2 align-middle"></i>
                        Match is currently live. Scores are updating in real-time.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Match Information & Accolades Cards Sidebar -->
    <div class="col-md-4">
        <!-- Date / Metadata Breakdown Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                    <i class="fa-solid fa-calendar text-secondary me-2"></i>Schedule Timeline
                </h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2.5 d-flex align-items-center">
                        <span class="text-muted small fw-bold ps-0" style="width: 30%;">Calendar:</span>
                        <span class="text-dark fw-semibold small">{{ $game->match_date->format('l, M d, Y') }}</span>
                    </li>
                    <li class="d-flex align-items-center">
                        <span class="text-muted small fw-bold ps-0" style="width: 30%;">Toss/Start:</span>
                        <span class="text-dark fw-semibold font-monospace small">{{ $game->match_date->format('h:i A') }} (Local)</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Awards, Winner & MVP Highlights Widget Container -->
        @if($game->winner || $game->playerOfTheMatch)
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                        <i class="fa-solid fa-award text-warning me-2"></i>Accolades & Awards
                    </h5>
                    
                    @if($game->winner)
                        <div class="d-flex align-items-center mb-3 bg-light p-2.5 rounded border">
                            <div class="p-2 bg-warning-subtle text-warning border border-warning rounded me-3 text-center" style="width: 40px;">
                                <i class="fa-solid fa-trophy fs-5"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block fs-9 fw-bold text-uppercase">Champions Match Winner</small>
                                <span class="fw-bold text-dark text-md">{{ $game->winner->name }}</span>
                            </div>
                        </div>
                    @endif

                    @if($game->playerOfTheMatch)
                        <div class="d-flex align-items-center bg-light p-2.5 rounded border">
                            <div class="p-2 bg-success-subtle text-success border border-success rounded me-3 text-center" style="width: 40px;">
                                <i class="fa-solid fa-medal fs-5"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block fs-9 fw-bold text-uppercase">Player of the Match (MVP)</small>
                                <span class="fw-bold text-dark text-md">{{ $game->playerOfTheMatch->name }}</span>
                                <small class="text-muted d-block font-monospace fs-8">ID: {{ $game->playerOfTheMatch->student_id }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection