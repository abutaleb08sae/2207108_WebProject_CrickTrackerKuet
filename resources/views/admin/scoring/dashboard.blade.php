@extends('layouts.admin')

@section('admin-content')
<div class="pt-3 pb-2 mb-3 border-bottom d-flex justify-content-between align-items-center">
    <h1 class="h2 fw-bold text-dark">Scoring Room: <span class="text-primary">{{ $fixture->teamOne->slug }} vs {{ $fixture->teamTwo->slug }}</span></h1>
    <a href="{{ route('scoring.index') }}" class="btn btn-sm btn-light border"><i class="fa-solid fa-arrow-left me-1"></i> Exit</a>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card bg-dark text-white border-0 shadow-sm mb-4">
            <div class="card-body text-center p-4">
                <p class="text-uppercase tracking-wider text-info mb-1 small fw-bold">{{ $score->current_innings }}</p>
                <h1 class="display-1 fw-black text-white my-2">
                    {{ $score->runs }} <span class="text-secondary">/</span> {{ $score->wickets }}
                </h1>
                <p class="fs-5 text-light opacity-75 mb-0">
                    Overs: <strong class="text-warning">{{ floor($score->balls_bowled / 6) }}.{{ $score->balls_bowled % 6 }}</strong> 
                    <span class="fs-6 text-muted">({{ $score->balls_bowled }} balls)</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-gamepad text-secondary me-2"></i>Scoring Controls</h5>
            </div>
            <div class="card-body p-4 pt-0">
                <form action="{{ route('scoring.update', $fixture->id) }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-6 col-sm-4">
                            <button type="submit" name="action" value="add_dot" class="btn btn-outline-secondary w-100 py-3 fw-bold">Dot Ball</button>
                        </div>
                        <div class="col-6 col-sm-4">
                            <button type="submit" name="action" value="add_run" class="btn btn-primary w-100 py-3 fw-bold">+1 Run</button>
                        </div>
                        <div class="col-6 col-sm-4">
                            <button type="submit" name="action" value="add_four" class="btn btn-success w-100 py-3 fw-bold">4 (FOUR)</button>
                        </div>
                        <div class="col-6 col-sm-4">
                            <button type="submit" name="action" value="add_six" class="btn btn-info text-white w-100 py-3 fw-bold">6 (SIX)</button>
                        </div>
                        <div class="col-6 col-sm-4">
                            <button type="submit" name="action" value="add_wide" class="btn btn-warning text-dark w-100 py-3 fw-bold">Wide (+1R)</button>
                        </div>
                        <div class="col-6 col-sm-4">
                            <button type="submit" name="action" value="add_noball" class="btn btn-warning text-dark w-100 py-3 fw-bold">No Ball (+1R)</button>
                        </div>
                    </div>
                    
                    <div class="border-top my-4 pt-3">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <button type="submit" name="action" value="add_wicket" class="btn btn-danger w-100 py-2 fw-bold" onclick="return confirm('Confirm wicket fall?');">
                                    <i class="fa-solid fa-skull-crossbones me-1"></i> OUT / Wicket
                                </button>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" name="action" value="reset" class="btn btn-link text-muted w-100 py-2 small border" onclick="return confirm('Reset all live metrics to zero?');">
                                    Reset Scorecard
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection