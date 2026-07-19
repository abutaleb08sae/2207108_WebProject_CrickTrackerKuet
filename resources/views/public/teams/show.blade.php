@extends('layouts.public')

@section('title', $team->name . ' | Team Profile')

@section('content')
<div class="container my-5 text-start">
    {{-- Back navigation --}}
    <div class="mb-4">
        <a href="{{ route('public.standings') }}" class="text-decoration-none fw-bold text-info">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Points Leaderboard
        </a>
    </div>

    {{-- Team Identity Hero Banner Deck --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); color: #fff;">
        <div class="card-body p-5">
            <div class="d-flex align-items-center gap-4">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-1 shadow shadow-lg" style="width: 90px; height: 90px;">
                    {{ strtoupper(substr($team->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="fw-bold mb-1 text-white">{{ $team->name }} Department</h1>
                    <p class="text-info mb-0 small fw-semibold"><i class="fa-solid fa-shield-halved me-1"></i> Official Tournament Roster Profile</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Roster Panel: Active Playing Squad --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom border-light">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-users text-primary me-2"></i>Active Squad Roster</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($team->players as $player)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 hover-bg-light">
                                <div>
                                    <a href="{{ route('public.players.show', $player->id) }}" class="text-decoration-none text-dark fw-bold hover-underline">
                                        🏃 {{ $player->name }}
                                    </a>
                                </div>
                                <span class="badge bg-light text-muted border px-2 py-1 small">{{ $player->role ?? 'All-Rounder' }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">No roster entities registered to this department yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- Right Roster Panel: Match Performance History Log --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom border-light">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-clock-rotate-left text-info me-2"></i>Match Performance History</h5>
                </div>
                <div class="card-body p-4">
                    <div class="timeline">
                        @forelse($fixtures as $f)
                            <div class="p-3 border rounded-3 mb-3 bg-light text-dark">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small font-monospace text-muted">{{ \Carbon\Carbon::parse($f->match_datetime)->format('M d, Y - h:i A') }}</span>
                                    @if($f->status == 'Live')
                                        <span class="badge bg-danger animate-pulse">LIVE</span>
                                    @elseif($f->status == 'Completed')
                                        <span class="badge bg-secondary">COMPLETED</span>
                                    @else
                                        <span class="badge bg-primary">UPCOMING</span>
                                    @endif
                                </div>
                                <div class="fw-bold mb-2">
                                    <a href="{{ route('public.matches.show', $f->id) }}" class="text-decoration-none text-dark hover-underline">
                                        {{ $f->teamOne?->name }} vs {{ $f->teamTwo?->name }}
                                    </a>
                                </div>
                                <small class="text-muted d-block"><i class="fa-solid fa-location-dot me-1"></i> {{ $f->venue }}</small>
                                @if($f->matchScore && $f->matchScore->match_result_string)
                                    <div class="mt-2 text-success small fw-bold">🏆 {{ $f->matchScore->match_result_string }}</div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">No competitive fixtures logged for this team execution bracket yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-bg-light:hover { background-color: #f8fafc; }
    .hover-underline:hover { text-decoration: underline !important; }
</style>
@endsection