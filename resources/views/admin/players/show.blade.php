@extends('layouts.admin')

@section('admin-content')
<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Player Profile Dashboard</h1>
</div>

<div class="mb-4 d-flex justify-content-between align-items-center">
    <a href="{{ route('players.index') }}" class="text-decoration-none fw-bold text-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Roster Directory
    </a>
    <div>
        <a href="{{ route('players.edit', $player->id) }}" class="btn btn-outline-secondary btn-sm me-2">
            <i class="fa-solid fa-user-pen me-1"></i> Edit Details
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Profile Summary Card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="card-body">
                <div class="mb-3 d-inline-block position-relative">
                    @if($player->photo_path || $player->image_path)
                        <img src="{{ asset('storage/' . ($player->photo_path ?? $player->image_path)) }}" alt="{{ $player->name }}" class="rounded-circle border profile-avatar shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="bg-light text-secondary rounded-circle border d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 120px; height: 120px; font-size: 36px;">
                            {{ substr($player->name, 0, 2) }}
                        </div>
                    @endif
                </div>

                <h4 class="fw-bold text-dark mb-1">{{ $player->name }}</h4>
                <p class="text-muted font-monospace small mb-3">ID: {{ $player->student_id }}</p>

                <div class="mb-3">
                    @if($player->team)
                        <span class="badge bg-dark px-3 py-2 fs-7">{{ $player->team->name }}</span>
                    @else
                        <span class="badge bg-secondary px-3 py-2 fs-7">Unassigned</span>
                    @endif
                </div>

                <span class="badge rounded-pill px-3 py-2 
                    @if($player->role == 'Batsman') bg-success 
                    @elseif($player->role == 'Bowler') bg-danger 
                    @elseif($player->role == 'All-rounder') bg-warning text-dark 
                    @else bg-info text-dark @endif">
                    {{ $player->role }}
                </span>
            </div>
        </div>
    </div>

    <!-- Stats and Biography -->
    <div class="col-md-8">
        <!-- Statistics Widget Grid -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-chart-simple text-primary me-2"></i>Career Metrics</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="p-3 bg-light rounded border">
                            <small class="text-muted fw-bold text-uppercase d-block mb-1 fs-8">Matches</small>
                            <span class="h3 fw-bold text-dark">{{ $player->matches_played }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-light rounded border">
                            <small class="text-success fw-bold text-uppercase d-block mb-1 fs-8">Runs</small>
                            <span class="h3 fw-bold text-success">{{ $player->total_runs }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-light rounded border">
                            <small class="text-danger fw-bold text-uppercase d-block mb-1 fs-8">Wickets</small>
                            <span class="h3 fw-bold text-danger">{{ $player->total_wickets }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meta Details & Biography Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-address-card text-secondary me-2"></i>Profile Background</h5>
            </div>
            <div class="card-body p-4">
                <table class="table table-sm table-borderless align-middle mb-4">
                    <tbody>
                        <tr>
                            <td class="text-muted fw-bold ps-0" style="width: 35%;">Batting Profile:</td>
                            <td class="text-dark fw-semibold">{{ $player->batting_style ?? 'Not Specified' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold ps-0">Bowling Profile:</td>
                            <td class="text-dark fw-semibold">{{ $player->bowling_style ?? 'Not Specified' }}</td>
                        </tr>
                        @if($player->jersey_number)
                        <tr>
                            <td class="text-muted fw-bold ps-0">Jersey Registration:</td>
                            <td class="text-dark fw-semibold">#{{ $player->jersey_number }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <h6 class="fw-bold text-secondary mb-2">Scouting & Biography Notes</h6>
                <div class="bg-light p-3 rounded border text-secondary small">
                    {{ $player->biography ?? 'No personal background description profile data registered for this player.' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection