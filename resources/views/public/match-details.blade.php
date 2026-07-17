@extends('layouts.public')

@section('title', isset($fixture) ? $fixture->teamOne->name . ' vs ' . $fixture->teamTwo->name . ' | Live Board' : ($match['name'] ?? 'Live Board'))

@section('content')
<div class="container my-5">
    <div class="mb-4">
        <a href="{{ route('public.home') }}" class="text-decoration-none fw-bold text-info">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Live Center
        </a>
    </div>

    @if(isset($fixture))
      
        <!-- LOCAL DETAILED MATCH TABBED LAYOUT (NEW)   -->
       
        
        <!-- Match Hero Deck -->
        <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%); color: #fff;">
            <div class="card-body p-5 text-center">
                <span class="badge bg-danger text-uppercase mb-3 px-3 py-2 fw-bold">
                    {{ $fixture->status }}
                </span>
                <h2 class="fw-bold mb-2">{{ $fixture->teamOne->name }} vs {{ $fixture->teamTwo->name }}</h2>
                <p class="text-info small fw-semibold mb-4">
                    <i class="fa-solid fa-location-dot me-1"></i> {{ $fixture->venue }}
                </p>

                <div class="row align-items-center justify-content-center my-4">
                    <div class="col-md-4 text-md-end text-center">
                        <h3 class="fw-bold mb-1">{{ $fixture->teamOne->name }}</h3>
                        <span class="text-muted small">({{ $fixture->teamOne->short_name ?? 'T1' }})</span>
                    </div>
                    <div class="col-md-2 text-center my-3 my-md-0">
                        <span class="fs-4 fw-bold px-3 py-2 rounded-circle bg-dark border border-secondary text-info">VS</span>
                    </div>
                    <div class="col-md-4 text-md-start text-center">
                        <h3 class="fw-bold mb-1">{{ $fixture->teamTwo->name }}</h3>
                        <span class="text-muted small">({{ $fixture->teamTwo->short_name ?? 'T2' }})</span>
                    </div>
                </div>

                <div class="border-top border-secondary pt-4 mt-4">
                    <p class="text-warning fw-bold mb-1">
                        @if($fixture->toss_winner_id)
                            Toss: {{ $fixture->toss_winner_id == $fixture->team_one_id ? $fixture->teamOne->name : $fixture->teamTwo->name }} won & elected to {{ $fixture->toss_decision }}
                        @else
                            Toss details pending
                        @endif
                    </p>
                    <p class="small text-muted mb-0">Date & Time: {{ \Carbon\Carbon::parse($fixture->match_datetime)->format('F j, Y, g:i a') }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4" id="matchTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active fw-bold text-dark" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary" type="button" role="tab">Summary</button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold text-dark" id="scorecard-tab" data-bs-toggle="tab" data-bs-target="#scorecard" type="button" role="tab">Scorecard</button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold text-dark" id="commentary-tab" data-bs-toggle="tab" data-bs-target="#commentary" type="button" role="tab">Commentary</button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold text-dark" id="xi-tab" data-bs-toggle="tab" data-bs-target="#xi" type="button" role="tab">Playing XI</button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold text-dark" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats" type="button" role="tab">Statistics</button>
            </li>
        </ul>

        <div class="tab-content bg-white p-4 border rounded-3 shadow-sm mb-5" id="matchTabContent">
            <!-- 1. SUMMARY TAB -->
            <div class="tab-pane fade show active" id="summary" role="tabpanel">
                <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-circle-info text-info me-2"></i>Match Overview</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <table class="table border align-middle">
                            <tbody>
                                <tr><th class="bg-light w-50">Target Runs</th><td>{{ $fixture->target_runs ?? 'N/A' }}</td></tr>
                                <tr><th class="bg-light">Current Run Rate (CRR)</th><td class="fw-bold text-success">{{ $currentRunRate ?? '0.00' }}</td></tr>
                                <tr><th class="bg-light">Required Run Rate (RRR)</th><td class="fw-bold text-danger">{{ $requiredRunRate ?? 'N/A' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded border h-100">
                            <h6 class="fw-bold text-dark"><i class="fa-solid fa-map-pin text-info me-1"></i> Match Conditions</h6>
                            <p class="mb-0 text-muted small">Official regulations apply. Dynamic targets and current match projections update ball-by-ball synchronously.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. SCORECARD TAB -->
            <div class="tab-pane fade" id="scorecard" role="tabpanel">
                @forelse($fixture->innings as $inning)
                    <div class="mb-4">
                        <div class="bg-dark text-white p-3 rounded-top fw-bold d-flex justify-content-between align-items-center">
                            <span>Innings {{ $inning->innings_number }}: {{ $inning->battingTeam->name }}</span>
                            <span class="text-info">{{ $inning->total_runs }}/{{ $inning->total_wickets }} ({{ $inning->formatted_overs }} Ov)</span>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover border align-middle mt-0 mb-3">
                                <thead class="table-light">
                                    <tr>
                                        <th>Batter</th>
                                        <th>Dismissal</th>
                                        <th class="text-center">R</th>
                                        <th class="text-center">B</th>
                                        <th class="text-center">4s</th>
                                        <th class="text-center">6s</th>
                                        <th class="text-center">SR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inning->battingScorecards as $batRow)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ $batRow->player->name }}</td>
                                            <td class="text-muted small">{{ $batRow->dismissal_description }}</td>
                                            <td class="text-center fw-bold text-success">{{ $batRow->runs }}</td>
                                            <td class="text-center">{{ $batRow->balls_faced }}</td>
                                            <td class="text-center">{{ $batRow->fours }}</td>
                                            <td class="text-center">{{ $batRow->sixes }}</td>
                                            <td class="text-center font-monospace text-secondary">{{ $batRow->strike_rate }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="7" class="text-center text-muted py-3">No batting rows recorded yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover border align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Bowler</th>
                                        <th class="text-center">O</th>
                                        <th class="text-center">M</th>
                                        <th class="text-center">R</th>
                                        <th class="text-center">W</th>
                                        <th class="text-center">Econ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inning->bowlingScorecards as $bowlRow)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ $bowlRow->player->name }}</td>
                                            <td class="text-center">{{ $bowlRow->formatted_overs }}</td>
                                            <td class="text-center">{{ $bowlRow->maidens }}</td>
                                            <td class="text-center">{{ $bowlRow->runs_conceded }}</td>
                                            <td class="text-center fw-bold text-danger">{{ $bowlRow->wickets_taken }}</td>
                                            <td class="text-center font-monospace">{{ $bowlRow->economy }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center text-muted py-3">No bowling rows recorded yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">No innings structural frames logged yet. Scorecard details populate live.</div>
                @endforelse
            </div>

            <!-- 3. COMMENTARY TAB -->
            <div class="tab-pane fade" id="commentary" role="tabpanel">
                <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-bullhorn text-info me-2"></i>Ball-by-Ball Commentary</h5>
                <div class="list-group list-group-flush">
                    @forelse($fixture->innings->flatMap->balls as $ball)
                        <div class="list-group-item d-flex align-items-start gap-3 py-3 px-0 border-bottom">
                            <span class="badge bg-dark font-monospace p-2 fs-6">{{ $ball->over_number }}.{{ $ball->ball_number }}</span>
                            <div>
                                <span class="fw-bold text-dark">{{ $ball->bowler->name ?? 'Bowler' }} to {{ $ball->batsman->name ?? 'Batter' }}</span>
                                @if($ball->is_wicket)
                                    <span class="badge bg-danger ms-2">WICKET</span>
                                @endif
                                <p class="mb-0 text-secondary mt-1 small">{{ $ball->commentary_text }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">Waiting for streaming feeds or live commentary entries.</div>
                    @endforelse
                </div>
            </div>

            <!-- 4. PLAYING XI TAB -->
            <div class="tab-pane fade" id="xi" role="tabpanel">
                <div class="row g-4">
                    <div class="col-md-6 border-end">
                        <h6 class="fw-bold border-bottom pb-2 text-dark"><i class="fa-solid fa-users text-info me-2"></i>{{ $fixture->teamOne->name }} Squad</h6>
                        <ul class="list-group list-group-flush">
                            @forelse($fixture->squads->where('team_id', $fixture->team_one_id) as $sq)
                                <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                                    <span>🏃 {{ $sq->player->name }}</span>
                                    <div>
                                        @if($sq->is_captain)<span class="badge bg-dark me-1">C</span>@endif
                                        @if($sq->is_wicket_keeper)<span class="badge bg-info text-dark">WK</span>@endif
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-muted ps-0">Playing lineup parameters not configured yet.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold border-bottom pb-2 text-dark"><i class="fa-solid fa-users text-info me-2"></i>{{ $fixture->teamTwo->name }} Squad</h6>
                        <ul class="list-group list-group-flush">
                            @forelse($fixture->squads->where('team_id', $fixture->team_two_id) as $sq)
                                <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                                    <span>🏃 {{ $sq->player->name }}</span>
                                    <div>
                                        @if($sq->is_captain)<span class="badge bg-dark me-1">C</span>@endif
                                        @if($sq->is_wicket_keeper)<span class="badge bg-info text-dark">WK</span>@endif
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-muted ps-0">Playing lineup parameters not configured yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 5. STATISTICS TAB -->
            <div class="tab-pane fade" id="stats" role="tabpanel">
                <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-chart-line text-info me-2"></i>Visual Analytics</h5>
                <div class="row text-center g-3 mt-2">
                    <div class="col-md-6">
                        <div class="p-5 border rounded bg-light text-muted">
                            <i class="fa-solid fa-chart-area fa-2x mb-2 text-secondary"></i>
                            <span class="d-block small fw-semibold">Run Rate Progression Chart Placeholder</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-5 border rounded bg-light text-muted">
                            <i class="fa-solid fa-circle-dot fa-2x mb-2 text-secondary"></i>
                            <span class="d-block small fw-semibold">Wagon Wheel Matrix Canvas Placeholder</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
      
        <!-- ORIGINAL EXTERNAL MATCH BACKUP LAYOUT      -->
      
        
        <!-- Match Hero Deck -->
        <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%); color: #fff;">
            <div class="card-body p-5 text-center">
                <span class="badge bg-info text-dark fw-bold text-uppercase mb-3 px-3 py-2">
                    {{ $match['matchType'] ?? 'Match Details' }}
                </span>
                <h2 class="fw-bold mb-2">{{ $match['name'] }}</h2>
                <p class="text-info small fw-semibold mb-4">
                    <i class="fa-solid fa-location-dot me-1"></i> {{ $match['venue'] ?? 'Stadium Venue TBA' }}
                </p>

                <div class="row align-items-center justify-content-center my-4">
                    <div class="col-md-4 text-md-end text-center">
                        <h3 class="fw-bold mb-1">{{ $match['teams'][0] ?? 'Team 1' }}</h3>
                    </div>
                    <div class="col-md-2 text-center my-3 my-md-0">
                        <span class="fs-4 fw-bold px-3 py-2 rounded-circle bg-dark border border-secondary text-info">VS</span>
                    </div>
                    <div class="col-md-4 text-md-start text-center">
                        <h3 class="fw-bold mb-1">{{ $match['teams'][1] ?? 'Team 2' }}</h3>
                    </div>
                </div>

                <div class="border-top border-secondary pt-4 mt-4">
                    <h5 class="text-warning fw-bold mb-1">{{ $match['status'] }}</h5>
                    <p class="small text-muted mb-0">Date & Time: {{ date('F j, Y, g:i a', strtotime($match['dateTimeGMT'])) }} GMT</p>
                </div>
            </div>
        </div>

        <!-- Live Performance Scorecard & Info -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-square-poll-vertical text-info me-2"></i>Live Status Information</h5>
                    </div>
                    <div class="card-body p-4">
                        @if(isset($match['score']) && count($match['score']) > 0)
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Innings</th>
                                            <th>Runs</th>
                                            <th>Wickets</th>
                                            <th>Overs</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($match['score'] as $scoreline)
                                            <tr>
                                                <td class="fw-bold text-dark">{{ $scoreline['inning'] }}</td>
                                                <td class="fw-bold text-success fs-5">{{ $scoreline['r'] }}</td>
                                                <td>{{ $scoreline['w'] }}</td>
                                                <td class="text-muted">{{ $scoreline['o'] }} ov</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-hourglass-start fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">Scores will begin displaying as soon as the match toss is completed and players take the field.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-circle-question text-info me-2"></i>Match Info</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <small class="text-muted d-block">Tournament / Series</small>
                            <span class="fw-bold text-dark">{{ $match['series_id'] ?? 'International Tournament Series' }}</span>
                        </div>
                        <hr class="text-muted">
                        <div class="mb-3">
                            <small class="text-muted d-block">Match Referees & Officials</small>
                            <span class="text-dark">{{ $match['referee'] ?? 'Official delegation assigned on-field' }}</span>
                        </div>
                        <hr class="text-muted">
                        <div>
                            <small class="text-muted d-block">Toss Status</small>
                            <span class="badge bg-success-subtle text-success p-2 mt-1">
                                <i class="fa-solid fa-coins me-1"></i> {{ $match['tossWinner'] ?? 'Waiting for coin flip' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection