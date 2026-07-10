@extends('layouts.app')

@section('title', 'Home | CRICKTRACKER-KUET')

@section('content')
<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">CRICKTRACKER-KUET</h1>
        <p class="lead">The Ultimate Hub for International Cricket updates and KUET Intra-Campus Tournaments.</p>
        <span class="badge bg-danger px-3 py-2 animate-pulse"><i class="fa-solid fa-circle-dot me-1"></i> System Online</span>
    </div>
</div>

<div class="container my-5">
    <div class="row text-center g-4">
        <div class="col-md-4">
            <div class="card card-custom p-4 bg-white">
                <i class="fa-solid fa-square-poll-horizontal fa-3x text-primary mb-3"></i>
                <h3 class="h5 fw-bold">Live Scoring</h3>
                <p class="text-muted">Stay updated with ball-by-ball actions across structural intra-campus departmental matches.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom p-4 bg-white">
                <i class="fa-solid fa-calendar-days fa-3x text-info mb-3"></i>
                <h3 class="h5 fw-bold">Schedules & Fixtures</h3>
                <p class="text-muted">Track match schedules, upcoming tournaments, and historical campus scorecards.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom p-4 bg-white">
                <i class="fa-solid fa-users-line fa-3x text-success mb-3"></i>
                <h3 class="h5 fw-bold">Team Analytics</h3>
                <p class="text-muted">Browse complete team profiles, squad rosters, and up-to-date player performance stats.</p>
            </div>
        </div>
    </div>
</div>
@endsection