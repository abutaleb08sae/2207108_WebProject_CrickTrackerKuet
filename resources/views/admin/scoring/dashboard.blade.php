@extends('layouts.admin')

@section('admin-content')
<div class="pt-3 pb-2 mb-3 border-bottom d-flex justify-content-between align-items-center">
    <h1 class="h2 fw-bold text-dark">Live Console: <span class="text-primary">{{ $fixture->teamOne->name }} vs {{ $fixture->teamTwo->name }}</span></h1>
    <a href="{{ route('scoring.index') }}" class="btn btn-sm btn-light border"><i class="fa-solid fa-arrow-left me-1"></i> Exit</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm text-start mb-4">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
@endif

@if(!$score->toss_winner_id)
    <div class="card border-0 shadow-sm p-4 text-start mx-auto" style="max-width: 600px;">
        <h4 class="fw-bold text-dark mb-3"><i class="fa-solid fa-circle-dollar-sign text-warning me-2"></i>Initialize Toss Details</h4>
        <form action="{{ route('scoring.toss', $fixture->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Who won the toss?</label>
                <select name="toss_winner_id" class="form-select" required>
                    <option value="">-- Choose Team --</option>
                    <option value="{{ $fixture->team_one_id }}">{{ $fixture->teamOne->name }}</option>
                    <option value="{{ $fixture->team_two_id }}">{{ $fixture->teamTwo->name }}</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Decision elected?</label>
                <select name="toss_decision" class="form-select" required>
                    <option value="BAT">Elected to Bat First</option>
                    <option value="BOWL">Elected to Bowl First</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Start Match Scoring</button>
        </form>
    </div>
@else
    @php
        $teamOneModel = $fixture->teamOne;
        $teamTwoModel = $fixture->teamTwo;
        $i1Batting = ($score->innings_one_batting_team_id == $fixture->team_one_id) ? $teamOneModel : $teamTwoModel;
        $i2Batting = ($score->innings_two_batting_team_id == $fixture->team_one_id) ? $teamOneModel : $teamTwoModel;
        
        $currentBattingTeamId = ($score->current_innings == 1) ? $score->innings_one_batting_team_id : $score->innings_two_batting_team_id;
        $battingRoster = ($currentBattingTeamId == $fixture->team_one_id) ? $team1Players : $team2Players;
        $bowlingRoster = ($currentBattingTeamId == $fixture->team_one_id) ? $team2Players : $team1Players;
    @endphp

    <!-- Active Lineup Management Panel -->
    <div class="card border-0 shadow-sm mb-4 bg-light text-start">
        <div class="card-body">
            <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-users me-2"></i>Active Lineup Management</h6>
            <form action="{{ route('scoring.active_players', $fixture->id) }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Batsman (On Strike)</label>
                        <select name="batsman_on_strike_id" class="form-select form-select-sm">
                            <option value="">-- Select Striker --</option>
                            @foreach($battingRoster as $p)
                                <option value="{{ $p->id }}" {{ $state->batsman_on_strike_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Batsman (Off Strike)</label>
                        <select name="batsman_off_strike_id" class="form-select form-select-sm">
                            <option value="">-- Select Non-Striker --</option>
                            @foreach($battingRoster as $p)
                                <option value="{{ $p->id }}" {{ $state->batsman_off_strike_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <div class="w-100">
                            <label class="form-label small fw-bold text-muted">Current Bowler</label>
                            <select name="current_bowler_id" class="form-select form-select-sm">
                                <option value="">-- Select Bowler --</option>
                                @foreach($bowlingRoster as $p)
                                    <option value="{{ $p->id }}" {{ $state->current_bowler_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-dark text-nowrap fw-bold px-3 shadow-sm" style="height: 31px;">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Board Interface -->
    <div class="row g-4 text-start">
        <div class="col-lg-5">
            <div class="card bg-dark text-white border-0 shadow-sm mb-3 text-center">
                <div class="card-body p-4">
                    <span class="badge bg-info text-dark px-3 py-1 rounded-pill mb-2 fw-bold text-uppercase">
                        Innings {{ $score->current_innings }} Active
                    </span>
                    <h5 class="text-white-50 fw-bold mb-1">
                        Batting: <span class="text-warning">{{ $score->current_innings == 1 ? $i1Batting->name : $i2Batting->name }}</span>
                    </h5>
                    
                    <h1 class="display-1 fw-bold my-2">
                        @if($score->current_innings == 1)
                            {{ $score->innings_one_runs }} / {{ $score->innings_one_wickets }}
                        @else
                            {{ $score->innings_two_runs }} / {{ $score->innings_two_wickets }}
                        @endif
                    </h1>
                    
                    <p class="fs-5 mb-0 opacity-75">
                        Overs: <strong class="text-info">
                            @if($score->current_innings == 1)
                                {{ floor($score->innings_one_balls / 6) }}.{{ $score->innings_one_balls % 6 }}
                            @else
                                {{ floor($score->innings_two_balls / 6) }}.{{ $score->innings_two_balls % 6 }}
                            @endif
                        </strong>
                    </p>
                </div>
            </div>

            <!-- Display Active Stats Modules -->
            <div class="card border-0 shadow-sm p-3 mb-3 bg-white">
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-2"><i class="fa-solid fa-bolt text-warning me-1"></i> Current Live Performers</h6>
                
                <div class="table-responsive small">
                    <table class="table table-sm table-borderless align-middle mb-2">
                        <thead>
                            <tr class="text-muted border-bottom">
                                <th>Batsman</th>
                                <th>R</th>
                                <th>B</th>
                                <th>4s</th>
                                <th>6s</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($state->striker)
                            <tr class="fw-bold text-success">
                                <td>{{ $state->striker->name }} *</td>
                                <td>{{ $state->striker->battingScorecards->where('fixture_id', $fixture->id)->first()?->runs_scored ?? 0 }}</td>
                                <td>{{ $state->striker->battingScorecards->where('fixture_id', $fixture->id)->first()?->balls_faced ?? 0 }}</td>
                                <td>{{ $state->striker->battingScorecards->where('fixture_id', $fixture->id)->first()?->fours_hit ?? 0 }}</td>
                                <td>{{ $state->striker->battingScorecards->where('fixture_id', $fixture->id)->first()?->sixes_hit ?? 0 }}</td>
                            </tr>
                            @endif
                            @if($state->nonStriker)
                            <tr class="text-dark">
                                <td>{{ $state->nonStriker->name }}</td>
                                <td>{{ $state->nonStriker->battingScorecards->where('fixture_id', $fixture->id)->first()?->runs_scored ?? 0 }}</td>
                                <td>{{ $state->nonStriker->battingScorecards->where('fixture_id', $fixture->id)->first()?->balls_faced ?? 0 }}</td>
                                <td>{{ $state->nonStriker->battingScorecards->where('fixture_id', $fixture->id)->first()?->fours_hit ?? 0 }}</td>
                                <td>{{ $state->nonStriker->battingScorecards->where('fixture_id', $fixture->id)->first()?->sixes_hit ?? 0 }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <table class="table table-sm table-borderless align-middle mb-0 mt-2">
                        <thead>
                            <tr class="text-muted border-bottom">
                                <th>Bowler</th>
                                <th>O</th>
                                <th>R</th>
                                <th>W</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($state->bowler)
                            @php 
                                $bowlerCard = $state->bowler->bowlingScorecards->where('fixture_id', $fixture->id)->first();
                            @endphp
                            <tr class="text-dark fw-bold">
                                <td>{{ $state->bowler->name }}</td>
                                <td>{{ $bowlerCard ? number_format($bowlerCard->overs_bowled, 1) : '0.0' }}</td>
                                <td>{{ $bowlerCard?->runs_conceded ?? 0 }}</td>
                                <td>{{ $bowlerCard?->wickets_taken ?? 0 }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Match Board Summary -->
            <div class="card border-0 shadow-sm p-3 bg-white">
                <h6 class="fw-bold text-muted border-bottom pb-2 mb-2">Match Board Summary</h6>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>1st Inns ({{ $i1Batting->name }}):</span>
                    <strong class="text-dark">{{ $score->innings_one_runs }}/{{ $score->innings_one_wickets }} ({{ floor($score->innings_one_balls / 6) }}.{{ $score->innings_one_balls % 6 }} Ov)</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>2nd Inns ({{ $i2Batting->name }}):</span>
                    <strong class="text-dark">{{ $score->innings_two_runs }}/{{ $score->innings_two_wickets }} ({{ floor($score->innings_two_balls / 6) }}.{{ $score->innings_two_balls % 6 }} Ov)</strong>
                </div>
                
                @if($score->current_innings == 2)
                    <div class="mt-3 p-2 bg-success bg-opacity-10 border border-success rounded text-center text-success fw-bold">
                        Target: {{ $score->innings_one_runs + 1 }} | Needed: {{ ($score->innings_one_runs + 1) - $score->innings_two_runs }} runs
                    </div>
                @endif
            </div>
        </div>

        <!-- Dynamic Control Dashboard Operations -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4">
                <form action="{{ route('scoring.update', $fixture->id) }}" method="POST">
                    @csrf
                    
                    <!-- Dynamic Ball Commentary Writebox Component -->
                    <div class="mb-3">
                        <label for="commentary_text" class="form-label small fw-bold text-dark"><i class="fa-solid fa-microphone me-1 text-primary"></i> Ball-by-Ball Commentary Description (Optional)</label>
                        <input type="text" class="form-control form-control-sm border-dark-subtle" id="commentary_text" name="commentary_text" placeholder="e.g. Four runs through extra cover! Beautiful shot.">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-4"><button type="submit" name="action" value="add_dot" class="btn btn-outline-secondary w-100 py-3 fw-bold">Dot Ball</button></div>
                        <div class="col-4"><button type="submit" name="action" value="add_run" class="btn btn-primary w-100 py-3 fw-bold">+1 Run</button></div>
                        <div class="col-4"><button type="submit" name="action" value="add_run_2" class="btn btn-primary w-100 py-3 fw-bold">+2 Runs</button></div>
                        <div class="col-4"><button type="submit" name="action" value="add_run_3" class="btn btn-primary w-100 py-3 fw-bold">+3 Runs</button></div>
                        <div class="col-4"><button type="submit" name="action" value="add_four" class="btn btn-success w-100 py-3 fw-bold">4 (FOUR)</button></div>
                        <div class="col-4"><button type="submit" name="action" value="add_run_5" class="btn btn-primary w-100 py-3 fw-bold">+5 Runs</button></div>
                        <div class="col-6"><button type="submit" name="action" value="add_six" class="btn btn-info text-white w-100 py-3 fw-bold">6 (SIX)</button></div>
                        <div class="col-3"><button type="submit" name="action" value="add_wide" class="btn btn-warning text-dark w-100 py-3 fw-bold">Wide</button></div>
                        <div class="col-3"><button type="submit" name="action" value="add_noball" class="btn btn-warning text-dark w-100 py-3 fw-bold">No Ball</button></div>
                    </div>

                    <div class="mb-4">
                        <button type="submit" name="action" value="add_wicket" class="btn btn-danger w-100 py-3 fw-bold" onclick="return confirm('Confirm player dismiss out?');">OUT / Wicket Fall</button>
                    </div>

                    <div class="border-top pt-3">
                        <div class="row g-2">
                            @if($score->current_innings == 1)
                                <div class="col-sm-8">
                                    <button type="submit" name="action" value="end_innings" class="btn btn-dark w-100 py-2 fw-bold" onclick="return confirm('End 1st innings?');">End 1st Innings</button>
                                </div>
                            @else
                                <div class="col-sm-8">
                                    <button type="button" class="btn btn-success w-100 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#mvpAwardModal">End & Finalize Match</button>
                                </div>
                            @endif
                            <div class="col-sm-4">
                                <button type="submit" name="action" value="reset" class="btn btn-outline-danger w-100 py-2 small fw-bold" onclick="return confirm('Reset whole match data?');">Reset All</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Finalize Match & Player of the Match Selector Modal UI Element -->
    <div class="modal fade" id="mvpAwardModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="mvpAwardModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered text-start">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="mvpAwardModalLabel"><i class="fa-solid fa-trophy me-2"></i>Conclude Match & Awards</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('scoring.update', $fixture->id) }}" method="POST">
                    @csrf
                    <!-- Pass end_match action via a hidden parameter to maintain standard execution signatures -->
                    <input type="hidden" name="action" value="end_match">
                    
                    <div class="modal-body p-4">
                        <p class="text-muted small">The match status will shift to <strong>Completed</strong>. Select the player whose on-field performance earned them the Player of the Match award:</p>
                        
                        <div class="mb-3">
                            <label for="player_of_the_match_id" class="form-label fw-bold text-dark">Player of the Match</label>
                            <select name="player_of_the_match_id" id="player_of_the_match_id" class="form-select text-dark border-dark-subtle" required>
                                <option value="">-- Choose MVP Player --</option>
                                <optgroup label="🛡️ {{ $fixture->teamOne->name }}">
                                    @foreach($team1Players as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="🛡️ {{ $fixture->teamTwo->name }}">
                                    @foreach($team2Players as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-success px-4 fw-bold">Declare Winner & Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<script>
// Clear commentary layout configurations upon submitting scoring clicks to keep the input responsive
document.querySelectorAll('button[type="submit"]').forEach(btn => {
    btn.addEventListener('click', function() {
        // Allow short delay for execution cycles before wiping string memory
        setTimeout(() => {
            const commBox = document.getElementById('commentary_text');
            if(commBox && this.value !== 'end_match') commBox.value = '';
        }, 100);
    });
});
</script>
@endsection