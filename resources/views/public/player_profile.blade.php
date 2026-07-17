@extends('layouts.public')

@section('title', $player->name . ' Profile - CRICKTRACKER-KUET')

@section('content')
<div class="container py-4 text-start">
    <!-- Header Profile Summary -->
    <div class="card p-4 border-0 shadow-sm bg-dark text-white rounded-3 mb-4">
        <div class="d-flex align-items-center flex-wrap gap-3">
            @if($player->photo_path || $player->image_path)
                <img src="{{ asset('storage/' . ($player->photo_path ?? $player->image_path)) }}" 
                     alt="{{ $player->name }}" 
                     class="rounded-circle object-fit-cover border border-secondary me-2" 
                     style="width: 80px; height: 80px;">
            @else
                <div class="bg-secondary rounded-circle text-center d-flex align-items-center justify-content-center fw-bold fs-2 text-uppercase me-2" style="width: 80px; height: 80px; min-width: 80px;">
                    {{ substr($player->name, 0, 2) }}
                </div>
            @endif
            <div>
                <h1 class="mb-1 fw-bold">{{ $player->name }}</h1>
                <p class="mb-0 text-white-50 fs-6">
                    <i class="fa-solid fa-users me-1"></i> Team: <span class="text-white fw-bold">{{ $player->team?->name ?? 'N/A' }}</span> 
                    | <i class="fa-solid fa-cricket-bat-ball ms-2 me-1"></i> Role: <span class="text-white">{{ $player->role ?? 'All-Rounder' }}</span>
                    @if($player->jersey_number)
                        | <i class="fa-solid fa-shirt ms-2 me-1"></i> No: <span class="text-white fw-bold">#{{ $player->jersey_number }}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Bio / Personal Information Metadata Ledger -->
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm rounded-3 bg-white">
                <h3 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-user text-info me-2"></i>Player Profile Info</h3>
                <div class="row g-3">
                    <div class="col-6 col-sm-3 bg-light p-3 rounded text-center">
                        <span class="text-muted d-block small">Student ID</span>
                        <strong class="text-dark">{{ $player->student_id ?? 'N/A' }}</strong>
                    </div>
                    <div class="col-6 col-sm-3 bg-light p-3 rounded text-center">
                        <span class="text-muted d-block small">Nationality</span>
                        <strong class="text-dark">{{ $player->nationality ?? 'Bangladeshi' }}</strong>
                    </div>
                    <div class="col-6 col-sm-3 bg-light p-3 rounded text-center">
                        <span class="text-muted d-block small">Batting Style</span>
                        <strong class="text-dark">{{ $player->batting_style ?? 'Right-hand bat' }}</strong>
                    </div>
                    <div class="col-6 col-sm-3 bg-light p-3 rounded text-center">
                        <span class="text-muted d-block small">Bowling Style</span>
                        <strong class="text-dark">{{ $player->bowling_style ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Batting Career Ledger -->
        <div class="col-12 col-md-6">
            <div class="card p-4 border-0 shadow-sm rounded-3 bg-white h-100">
                <h3 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-font-awesome text-primary me-2"></i>Batting Records</h3>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle m-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mat</th>
                                <th>Inn</th>
                                <th>Runs</th>
                                <th>HS</th>
                                <th>Avg</th>
                                <th>SR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">{{ $stats['matches'] }}</td>
                                <td>{{ $stats['innings'] }}</td>
                                <td class="text-success fw-bold fs-5">{{ $stats['runs'] }}</td>
                                <td>{{ $stats['highest'] }}</td>
                                <td>{{ $stats['average'] }}</td>
                                <td class="fw-bold text-secondary">{{ $stats['strike_rate'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row text-center mt-3 g-2">
                    <div class="col-6 bg-light py-2 rounded border-end">Total 4s: <strong>{{ $stats['fours'] }}</strong></div>
                    <div class="col-6 bg-light py-2 rounded">Total 6s: <strong>{{ $stats['sixes'] }}</strong></div>
                </div>
            </div>
        </div>

        <!-- Bowling Career Ledger -->
        <div class="col-12 col-md-6">
            <div class="card p-4 border-0 shadow-sm rounded-3 bg-white h-100">
                <h3 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-baseball text-danger me-2"></i>Bowling Records</h3>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle m-0">
                        <thead class="table-light">
                            <tr>
                                <th>Overs</th>
                                <th>Runs</th>
                                <th>Wkts</th>
                                <th>Econ</th>
                                <th>Avg</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">{{ $bowling['overs'] }}</td>
                                <td>{{ $bowling['runs_conceded'] }}</td>
                                <td class="text-danger fw-bold fs-5">{{ $bowling['wickets'] }}</td>
                                <td>{{ $bowling['economy'] }}</td>
                                <td>{{ $bowling['average'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Match Performance History Log -->
    @if($battingRecords->count() > 0)
    <div class="card p-4 border-0 shadow-sm rounded-3 bg-white mb-4">
        <h3 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-clock-rotate-left text-secondary me-2"></i>Recent Matches</h3>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle m-0">
                <thead class="table-light">
                    <tr>
                        <th>Runs</th>
                        <th>Balls</th>
                        <th>4s</th>
                        <th>6s</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($battingRecords->take(5) as $record)
                    <tr>
                        <td class="fw-bold text-dark">{{ $record->runs }}</td>
                        <td>{{ $record->balls_faced }}</td>
                        <td>{{ $record->fours }}</td>
                        <td>{{ $record->sixes }}</td>
                        <td>
                            <span class="badge {{ $record->status === 'Not Out' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $record->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection