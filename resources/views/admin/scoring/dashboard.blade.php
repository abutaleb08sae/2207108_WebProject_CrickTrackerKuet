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
    @endphp

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

            <div class="card border-0 shadow-sm p-3 bg-white">
                <h6 class="fw-bold text-muted border-bottom pb-2 mb-2">Match Board Summary</h6>
                <div class="d-flex" style="justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span>1st Inns ({{ $i1Batting->name }}):</span>
                    <strong class="text-dark">{{ $score->innings_one_runs }}/{{ $score->innings_one_wickets }} ({{ floor($score->innings_one_balls / 6) }}.{{ $score->innings_one_balls % 6 }} Ov)</strong>
                </div>
                <div class="d-flex" style="justify-content: space-between; align-items: center;">
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

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4">
                <form action="{{ route('scoring.update', $fixture->id) }}" method="POST">
                    @csrf
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
                                    <button type="submit" name="action" value="end_match" class="btn btn-success w-100 py-2 fw-bold" onclick="return confirm('Calculate results and declare winner?');">End & Finalize Match</button>
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
@endif
@endsection