@extends('layouts.public')

@section('title', isset($fixture) ? ($fixture->teamOne?->name . ' vs ' . $fixture->teamTwo?->name . ' | Live Board') : ($match['name'] ?? 'Live Board'))

@section('content')
<div class="container my-5 text-start">
    {{-- Back Button Link --}}
    <div class="mb-4">
        <a href="{{ route('public.home') }}" class="text-decoration-none fw-bold text-info">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Live Center
        </a>
    </div>

    @if(isset($fixture))
        {{-- ========================================== --}}
        {{--      LOCAL DETAILED MATCH TABBED LAYOUT     --}}
        {{-- ========================================== --}}
        
        {{-- Match Hero Deck --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%); color: #fff;">
            <div class="card-body p-5 text-center">
                <span class="badge bg-danger text-uppercase mb-3 px-3 py-2 fw-bold animate-pulse">
                    {{ $fixture->status }}
                </span>
                
                <h2 class="fw-bold mb-2">
                    {{ $fixture->teamOne?->name }} 
                    @if($fixture->matchScore) 
                        ({{ $fixture->matchScore->innings_one_batting_team_id == $fixture->team_one_id ? $fixture->matchScore->innings_one_runs.'/'.$fixture->matchScore->innings_one_wickets : $fixture->matchScore->innings_two_runs.'/'.$fixture->matchScore->innings_two_wickets }})
                    @endif
                    vs 
                    {{ $fixture->teamTwo?->name }}
                    @if($fixture->matchScore) 
                        ({{ $fixture->matchScore->innings_one_batting_team_id == $fixture->team_two_id ? $fixture->matchScore->innings_one_runs.'/'.$fixture->matchScore->innings_one_wickets : $fixture->matchScore->innings_two_runs.'/'.$fixture->matchScore->innings_two_wickets }})
                    @endif
                </h2>
                
                @if($fixture->matchScore && $fixture->status !== 'UPCOMING')
                    @php
                        $ballsBowled = (int) ($fixture->matchScore->balls_bowled ?? 0);
                        $displayOvers = floor($ballsBowled / 6) . '.' . ($ballsBowled % 6);
                    @endphp
                    <h1 class="display-5 fw-bold text-warning my-3">
                        Total: {{ $fixture->matchScore->runs ?? 0 }} / {{ $fixture->matchScore->wickets ?? 0 }}
                        <div class="fs-6 text-white-50 mt-1 fw-normal">
                            Overs: {{ $displayOvers }}
                        </div>
                    </h1>
                @endif

                <p class="text-info small fw-semibold mb-4">
                    <i class="fa-solid fa-location-dot me-1"></i> {{ $fixture->venue }}
                </p>

                <div class="border-top border-secondary pt-4 mt-4">
                    <p class="text-warning fw-bold mb-1">
                        @if($fixture->matchScore && $fixture->matchScore->match_result_string)
                            🎉 {{ $fixture->matchScore->match_result_string }}
                        @elseif($fixture->toss_winner_id || ($fixture->matchScore && isset($fixture->matchScore->toss_winner_id)))
                            @php
                                $tossWinnerId = $fixture->toss_winner_id ?? $fixture->matchScore->toss_winner_id;
                                $tossDecision = $fixture->toss_decision ?? $fixture->matchScore->toss_decision ?? 'bat';
                                $tossWinnerName = ($tossWinnerId == $fixture->team_one_id) ? $fixture->teamOne?->name : $fixture->teamTwo?->name;
                            @endphp
                            🪙 Toss: {{ $tossWinnerName }} won & elected to {{ $tossDecision }} first
                        @else
                            Toss details pending
                        @endif
                    </p>
                    <p class="small text-muted mb-0">Date & Time: {{ \Carbon\Carbon::parse($fixture->match_datetime)->format('F j, Y, g:i a') }}</p>
                </div>
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <ul class="nav nav-tabs mb-4" id="matchTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold text-dark" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary" type="button" role="tab" aria-controls="summary" aria-selected="true">Summary</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-dark" id="scorecard-tab" data-bs-toggle="tab" data-bs-target="#scorecard" type="button" role="tab" aria-controls="scorecard" aria-selected="false">Scorecard</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-dark" id="commentary-tab" data-bs-toggle="tab" data-bs-target="#commentary" type="button" role="tab" aria-controls="commentary" aria-selected="false">Commentary</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-dark" id="xi-tab" data-bs-toggle="tab" data-bs-target="#xi" type="button" role="tab" aria-controls="xi" aria-selected="false">Playing XI</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-dark" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats" type="button" role="tab" aria-controls="stats" aria-selected="false">Statistics</button>
            </li>
        </ul>

        <div class="tab-content bg-white p-4 border rounded-3 shadow-sm mb-5" id="matchTabContent">
            {{-- 1. SUMMARY TAB --}}
            <div class="tab-pane fade show active" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-circle-info text-info me-2"></i>Match Overview</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <table class="table border align-middle">
                            <tbody>
                                <tr>
                                    <th class="bg-light w-50">Target Runs</th>
                                    <td>{{ $fixture->matchScore?->target_runs ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Current Run Rate (CRR)</th>
                                    <td class="fw-bold text-success">
                                        @if($fixture->matchScore && $fixture->matchScore->balls_bowled > 0)
                                            {{ number_format(($fixture->matchScore->runs / ($fixture->matchScore->balls_bowled / 6)), 2) }}
                                        @else
                                            0.00
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Required Run Rate (RRR)</th>
                                    <td class="fw-bold text-danger">
                                        @if($fixture->matchScore && $fixture->matchScore->target_runs && ($fixture->matchScore->total_overs * 6) > $fixture->matchScore->balls_bowled)
                                            @php 
                                                $remainingRuns = $fixture->matchScore->target_runs - $fixture->matchScore->runs;
                                                $remainingOvers = ($fixture->matchScore->total_overs * 6 - $fixture->matchScore->balls_bowled) / 6;
                                            @endphp
                                            {{ $remainingRuns > 0 ? ($remainingOvers > 0 ? number_format($remainingRuns / $remainingOvers, 2) : 'Completed') : 'Completed' }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded border h-100">
                            <h6 class="fw-bold text-dark"><i class="fa-solid fa-map-pin text-info me-1"></i> Match Conditions</h6>
                            <p class="mb-0 text-muted small">Official regulations apply. Dynamic targets and current match projections update ball-by-ball synchronously from the live score management table dashboard.</p>
                        </div>
                    </div>
                </div>

                @if($fixture->player_of_the_match_id)
                    <div class="mt-4 p-3 bg-warning bg-opacity-10 border border-warning rounded d-flex justify-content-between align-items-center">
                        <span class="text-dark fw-bold"><i class="fa-solid fa-trophy text-warning me-2"></i>Player of the Match:</span>
                        <strong class="text-success fs-5">{{ $fixture->playerOfTheMatch?->name }}</strong>
                    </div>
                @endif
            </div>

            {{-- 2. SCORECARD TAB --}}
            <div class="tab-pane fade" id="scorecard" role="tabpanel" aria-labelledby="scorecard-tab">
                @if($fixture->status !== 'UPCOMING')
                    {{-- Innings Nav Switcher --}}
                    <ul class="nav nav-pills mb-4 gap-2" id="inningsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active btn-sm fw-bold border" id="local-inns1-tab" data-bs-toggle="tab" data-bs-target="#local-inns1" type="button" role="tab" aria-controls="local-inns1" aria-selected="true">1st Innings</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link btn-sm fw-bold border" id="local-inns2-tab" data-bs-toggle="tab" data-bs-target="#local-inns2" type="button" role="tab" aria-controls="local-inns2" aria-selected="false">2nd Innings</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="inningsTabContent">
                        {{-- 1st Innings Panel --}}
                        <div class="tab-pane fade show active" id="local-inns1" role="tabpanel" aria-labelledby="local-inns1-tab">
                            @php 
                                $inns1BattingId = $fixture->matchScore?->innings_one_batting_team_id ?? $fixture->team_one_id; 
                                $inns1BattingName = $inns1BattingId == $fixture->team_one_id ? $fixture->teamOne?->name : $fixture->teamTwo?->name;
                                $inns1BowlingName = $inns1BattingId == $fixture->team_one_id ? $fixture->teamTwo?->name : $fixture->teamOne?->name;
                                $inns1BowlingId = $inns1BattingId == $fixture->team_one_id ? $fixture->team_two_id : $fixture->team_one_id;
                            @endphp
                            
                            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">{{ $inns1BattingName }} <span class="text-muted small fs-6">(Batting)</span></h5>
                            <div class="table-responsive mb-4">
                                <table class="table table-hover border align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Clarification / Batter</th>
                                            <th>Status</th>
                                            <th class="text-center">R</th>
                                            <th class="text-center">B</th>
                                            <th class="text-center">4s</th>
                                            <th class="text-center">6s</th>
                                            <th class="text-center">SR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($fixture->battingScorecards ?? collect())->where('team_id', $inns1BattingId) as $card)
                                            <tr>
                                                <td class="fw-bold">
                                                    @if($card->player_id)
                                                        <a href="{{ route('public.players.show', $card->player_id) }}" class="text-decoration-none text-primary hover-underline">
                                                            {{ $card->player?->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-primary">{{ $card->player?->name ?? 'Unknown Player' }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-muted small">{{ $card->out_status ?? 'Not Out / Yet to Bat' }}</td>
                                                <td class="text-center fw-bold text-success">{{ $card->runs_scored }}</td>
                                                <td class="text-center">{{ $card->balls_faced }}</td>
                                                <td class="text-center">{{ $card->fours_hit }}</td>
                                                <td class="text-center">{{ $card->sixes_hit }}</td>
                                                <td class="text-center font-monospace text-secondary">{{ ($card->balls_faced ?? 0) > 0 ? number_format(($card->runs_scored / $card->balls_faced) * 100, 2) : '0.00' }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="7" class="text-muted py-3 text-center">No batting statistics logged for this innings yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">{{ $inns1BowlingName }} <span class="text-muted small fs-6">(Bowling)</span></h5>
                            <div class="table-responsive">
                                <table class="table table-hover border align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Bowler</th>
                                            <th class="text-center">O</th>
                                            <th class="text-center">R</th>
                                            <th class="text-center">W</th>
                                            <th class="text-center">Econ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($fixture->bowlingScorecards ?? collect())->where('team_id', $inns1BowlingId) as $card)
                                            <tr>
                                                <td class="fw-bold">
                                                    @if($card->player_id)
                                                        <a href="{{ route('public.players.show', $card->player_id) }}" class="text-decoration-none text-dark hover-underline">
                                                            {{ $card->player?->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-dark">{{ $card->player?->name ?? 'Unknown Bowler' }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ number_format($card->overs_bowled, 1) }}</td>
                                                <td class="text-center">{{ $card->runs_conceded }}</td>
                                                <td class="text-center fw-bold text-danger">{{ $card->wickets_taken }}</td>
                                                <td class="text-center font-monospace text-secondary">
                                                    @php $totalOvers = floor(($card->balls_bowled ?? 0) / 6) + ((($card->balls_bowled ?? 0) % 6) / 6); @endphp
                                                    {{ $totalOvers > 0 ? number_format($card->runs_conceded / $totalOvers, 2) : '0.00' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-muted py-3 text-center">No bowling statistics logged for this innings yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- 2nd Innings Panel --}}
                        <div class="tab-pane fade" id="local-inns2" role="tabpanel" aria-labelledby="local-inns2-tab">
                            @php 
                                $inns2BattingId = $fixture->matchScore?->innings_two_batting_team_id ?? $fixture->team_two_id; 
                                $inns2BattingName = $inns2BattingId == $fixture->team_one_id ? $fixture->teamOne?->name : $fixture->teamTwo?->name;
                                $inns2BowlingName = $inns2BattingId == $fixture->team_one_id ? $fixture->teamTwo?->name : $fixture->teamOne?->name;
                                $inns2BowlingId = $inns2BattingId == $fixture->team_one_id ? $fixture->team_two_id : $fixture->team_one_id;
                            @endphp

                            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">{{ $inns2BattingName }} <span class="text-muted small fs-6">(Batting)</span></h5>
                            <div class="table-responsive mb-4">
                                <table class="table table-hover border align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Batter</th>
                                            <th>Status</th>
                                            <th class="text-center">R</th>
                                            <th class="text-center">B</th>
                                            <th class="text-center">4s</th>
                                            <th class="text-center">6s</th>
                                            <th class="text-center">SR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($fixture->battingScorecards ?? collect())->where('team_id', $inns2BattingId) as $card)
                                            <tr>
                                                <td class="fw-bold">
                                                    @if($card->player_id)
                                                        <a href="{{ route('public.players.show', $card->player_id) }}" class="text-decoration-none text-primary hover-underline">
                                                            {{ $card->player?->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-primary">{{ $card->player?->name ?? 'Unknown Player' }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-muted small">{{ $card->out_status ?? 'Not Out / Yet to Bat' }}</td>
                                                <td class="text-center fw-bold text-success">{{ $card->runs_scored }}</td>
                                                <td class="text-center">{{ $card->balls_faced }}</td>
                                                <td class="text-center">{{ $card->fours_hit }}</td>
                                                <td class="text-center">{{ $card->sixes_hit }}</td>
                                                <td class="text-center font-monospace text-secondary">{{ ($card->balls_faced ?? 0) > 0 ? number_format(($card->runs_scored / $card->balls_faced) * 100, 2) : '0.00' }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="7" class="text-muted py-3 text-center">No batting statistics logged for this innings yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">{{ $inns2BowlingName }} <span class="text-muted small fs-6">(Bowling)</span></h5>
                            <div class="table-responsive">
                                <table class="table table-hover border align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Bowler</th>
                                            <th class="text-center">O</th>
                                            <th class="text-center">R</th>
                                            <th class="text-center">W</th>
                                            <th class="text-center">Econ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($fixture->bowlingScorecards ?? collect())->where('team_id', $inns2BowlingId) as $card)
                                            <tr>
                                                <td class="fw-bold">
                                                    @if($card->player_id)
                                                        <a href="{{ route('public.players.show', $card->player_id) }}" class="text-decoration-none text-dark hover-underline">
                                                            {{ $card->player?->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-dark">{{ $card->player?->name ?? 'Unknown Bowler' }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ number_format($card->overs_bowled, 1) }}</td>
                                                <td class="text-center">{{ $card->runs_conceded }}</td>
                                                <td class="text-center fw-bold text-danger">{{ $card->wickets_taken }}</td>
                                                <td class="text-center font-monospace text-secondary">
                                                    @php $totalOvers = floor(($card->balls_bowled ?? 0) / 6) + ((($card->balls_bowled ?? 0) % 6) / 6); @endphp
                                                    {{ $totalOvers > 0 ? number_format($card->runs_conceded / $totalOvers, 2) : '0.00' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-muted py-3 text-center">No bowling statistics logged for this innings yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted py-5">No innings structural frames logged yet. Scorecard details populate live after match initialization.</div>
                @endif
            </div>

            {{-- 3. COMMENTARY TAB --}}
            <div class="tab-pane fade" id="commentary" role="tabpanel" aria-labelledby="commentary-tab">
                <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-bullhorn text-info me-2"></i>Ball-by-Ball Commentary</h5>
                <div class="list-group list-group-flush">
                    @forelse($fixture->commentaries as $log)
                        <div class="list-group-item d-flex align-items-start gap-3 py-3 px-0 border-bottom">
                            <span class="badge bg-dark font-monospace p-2 fs-6" style="min-width: 65px;">
                                Ov {{ number_format($log->over_number, 1) }}
                            </span>
                            <div>
                                @php
                                    $badgeColor = 'bg-secondary';
                                    if ($log->runs_scored == 4) $badgeColor = 'bg-success';
                                    elseif ($log->runs_scored == 6) $badgeColor = 'bg-info';
                                    if ($log->ball_type == 'Wicket') $badgeColor = 'bg-danger';
                                @endphp
                                <span class="badge {{ $badgeColor }} me-2">
                                    {{ $log->ball_type == 'Wicket' ? 'WICKET' : ($log->runs_scored == 0 ? 'Dot' : $log->runs_scored . ' Runs') }}
                                </span>
                                <p class="mb-0 text-secondary d-inline-block">{{ $log->description }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">Waiting for streaming feeds or live commentary entries.</div>
                    @endforelse
                </div>
            </div>

            {{-- 4. PLAYING XI TAB --}}
            <div class="tab-pane fade" id="xi" role="tabpanel" aria-labelledby="xi-tab">
                <div class="row g-4">
                    <div class="col-md-6 border-end">
                        <h6 class="fw-bold border-bottom pb-2 text-dark"><i class="fa-solid fa-users text-info me-2"></i>{{ $fixture->teamOne?->name }} Squad</h6>
                        <ul class="list-group list-group-flush">
                            @forelse($fixture->teamOne->players as $player)
                                <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                                    <span>🏃 
                                        <a href="{{ route('public.players.show', $player->id) }}" class="text-decoration-none text-dark hover-underline fw-medium">
                                            {{ $player->name }}
                                        </a>
                                    </span>
                                    <div>
                                        <span class="badge bg-light text-muted border">{{ $player->role ?? 'Player' }}</span>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-muted ps-0">Playing lineup parameters not configured yet.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold border-bottom pb-2 text-dark"><i class="fa-solid fa-users text-info me-2"></i>{{ $fixture->teamTwo?->name }} Squad</h6>
                        <ul class="list-group list-group-flush">
                            @forelse($fixture->teamTwo->players as $player)
                                <li class="list-group-item d-flex justify-content-between align-items-center ps-0 border-0 d-flex justify-content-between">
                                    <span>🏃 
                                        <a href="{{ route('public.players.show', $player->id) }}" class="text-decoration-none text-dark hover-underline fw-medium">
                                            {{ $player->name }}
                                        </a>
                                    </span>
                                    <div>
                                        <span class="badge bg-light text-muted border">{{ $player->role ?? 'Player' }}</span>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-muted ps-0">Playing lineup parameters not configured yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- 5. STATISTICS TAB --}}
            <div class="tab-pane fade" id="stats" role="tabpanel" aria-labelledby="stats-tab">
                <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-chart-line text-info me-2"></i>Match Metric Comparison</h5>
                <div class="row text-center g-3 mt-2 text-dark">
                    <div class="col-6 col-md-3">
                        <div class="p-3 border rounded bg-light">
                            <div class="small text-muted mb-1">Total Boundaries</div>
                            <h4 class="fw-bold mb-0 text-primary">
                                {{ ($fixture->battingScorecards->sum('fours_hit') ?? 0) + ($fixture->battingScorecards->sum('sixes_hit') ?? 0) }}
                            </h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 border rounded bg-light">
                            <div class="small text-muted mb-1">Total Fours (4s)</div>
                            <h4 class="fw-bold mb-0 text-success">{{ $fixture->battingScorecards->sum('fours_hit') ?? 0 }}</h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 border rounded bg-light">
                            <div class="small text-muted mb-1">Total Sixes (6s)</div>
                            <h4 class="fw-bold mb-0 text-info">{{ $fixture->battingScorecards->sum('sixes_hit') ?? 0 }}</h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 border rounded bg-light">
                            <div class="small text-muted mb-1">Wickets Taken</div>
                            <h4 class="fw-bold mb-0 text-danger">{{ $fixture->bowlingScorecards->sum('wickets_taken') ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- ========================================== --}}
        {{--    ORIGINAL EXTERNAL MATCH BACKUP LAYOUT   --}}
        {{-- ========================================== --}}
      
        {{-- Match Hero Deck --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%); color: #fff;">
            <div class="card-body p-5 text-center">
                <span class="badge bg-info text-dark fw-bold text-uppercase mb-3 px-3 py-2">
                    {{ $match['matchType'] ?? 'Match Details' }}
                </span>
                <h2 class="fw-bold mb-2">{{ $match['name'] ?? 'Match' }}</h2>
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
                    <h5 class="text-warning fw-bold mb-1">{{ $match['status'] ?? 'N/A' }}</h5>
                    <p class="small text-muted mb-0">Date & Time: {{ isset($match['dateTimeGMT']) ? date('F j, Y, g:i a', strtotime($match['dateTimeGMT'])) : 'TBD' }} GMT</p>
                </div>
            </div>
        </div>

        {{-- Live Performance Scorecard & Info --}}
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-square-poll-vertical text-info me-2"></i>Live Status Information</h5>
                    </div>
                    <div class="card-body p-4">
                        @if(isset($match['score']) && is_array($match['score']) && count($match['score']) > 0)
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
                                                <td class="fw-bold text-dark">{{ $scoreline['inning'] ?? '' }}</td>
                                                <td class="fw-bold text-success fs-5">{{ $scoreline['r'] ?? 0 }}</td>
                                                <td>{{ $scoreline['w'] ?? 0 }}</td>
                                                <td class="text-muted">{{ $scoreline['o'] ?? 0 }} ov</td>
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