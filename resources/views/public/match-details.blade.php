@extends('layouts.public')

@section('title', $match['name'] . ' | Live Board')

@section('content')
<div class="container my-5">
    <div class="mb-4">
        <a href="{{ route('public.home') }}" class="text-decoration-none fw-bold text-info">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Live Center
        </a>
    </div>

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
</div>
@endsection