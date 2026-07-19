@extends('layouts.public')

@section('title', ($match['matchInfo']['team1']['teamName'] ?? 'Team 1') . ' vs ' . ($match['matchInfo']['team2']['teamName'] ?? 'Team 2') . ' - Live Score')

@section('content')
<div class="container py-4 text-start" style="max-width: 900px; font-family: sans-serif;">
    <!-- Match Header Card -->
    <div class="card p-4 border-0 shadow-sm bg-dark text-white rounded-3 mb-4">
        <span class="badge bg-danger text-uppercase mb-2 align-self-start" style="letter-spacing: 0.5px; font-size: 11px;">
            <i class="fa-solid fa-globe me-1"></i> International Live Stream
        </span>
        <h6 class="text-white-50 text-uppercase fw-bold mb-2 small">
            {{ $match['seriesName'] ?? $match['matchInfo']['seriesName'] ?? 'International Tournament' }}
        </h6>
        <h1 class="fw-bold mb-2 fs-2">
            {{ $match['matchInfo']['team1']['teamName'] ?? 'Team A' }} 
            <span class="text-white-50 fs-4 mx-1">vs</span> 
            {{ $match['matchInfo']['team2']['teamName'] ?? 'Team B' }}
        </h1>
        <p class="mb-0 text-warning fw-bold fs-5 mt-2">
            Status: {{ $match['miniscore']['status'] ?? $match['matchInfo']['status'] ?? 'Match Live' }}
        </p>
    </div>

    <!-- Score Summary Area -->
    <div class="card p-4 border-0 shadow-sm rounded-3 bg-white">
        <h3 class="fw-bold border-bottom pb-2 mb-3 text-dark fs-4">
            <i class="fa-solid fa-square-poll-horizontal text-primary me-2"></i>Live Score Summary
        </h3>
        
        <div class="p-4 bg-light rounded mb-3">
            <!-- Team 1 Row -->
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <span class="fs-5 fw-bold text-dark">{{ $match['matchInfo']['team1']['teamName'] ?? 'Team 1' }}</span>
                <span class="font-monospace fw-bold fs-4 text-primary">
                    @if(isset($match['miniscore']['batTeamScore']['runs']))
                        {{-- Handles Cricbuzz Live Miniscore Format --}}
                        {{ $match['miniscore']['batTeamScore']['runs'] }}/{{ $match['miniscore']['batTeamScore']['wickets'] ?? 0 }} 
                        <span class="fs-6 text-muted fw-normal">({{ $match['miniscore']['batTeamScore']['overs'] ?? 0 }} ov)</span>
                    @elseif(isset($match['matchScore']['team1Score']['inngs1']['runs']))
                        {{-- Fallback Format --}}
                        {{ $match['matchScore']['team1Score']['inngs1']['runs'] }}/{{ $match['matchScore']['team1Score']['inngs1']['wickets'] ?? 0 }}
                    @else
                        <span class="text-muted fs-6 fw-normal">Yet to bat</span>
                    @endif
                </span>
            </div>
            
            <!-- Team 2 Row -->
            <div class="d-flex justify-content-between align-items-center">
                <span class="fs-5 fw-bold text-dark">{{ $match['matchInfo']['team2']['teamName'] ?? 'Team 2' }}</span>
                <span class="font-monospace fw-bold fs-4 text-primary">
                    @if(isset($match['miniscore']['bowlTeamScore']['runs']))
                        {{ $match['miniscore']['bowlTeamScore']['runs'] }}/{{ $match['miniscore']['bowlTeamScore']['wickets'] ?? 0 }}
                        <span class="fs-6 text-muted fw-normal">({{ $match['miniscore']['bowlTeamScore']['overs'] ?? 0 }} ov)</span>
                    @elseif(isset($match['matchScore']['team2Score']['inngs1']['runs']))
                        {{ $match['matchScore']['team2Score']['inngs1']['runs'] }}/{{ $match['matchScore']['team2Score']['inngs1']['wickets'] ?? 0 }}
                    @else
                        <span class="text-muted fs-6 fw-normal">Yet to bat</span>
                    @endif
                </span>
            </div>
        </div>

        <!-- Custom Innings/Live Description Box if API provides it -->
        @if(isset($match['miniscore']['customStatus']))
            <div class="alert alert-info fw-bold mb-3">
                {{ $match['miniscore']['customStatus'] }}
            </div>
        @endif

        <!-- Venue Metadata Ledger Footer -->
        <div class="text-muted small mt-3">
            <i class="fa-solid fa-location-dot me-1"></i> Venue: <strong>{{ $match['matchInfo']['venueInfo']['ground'] ?? $match['matchInfo']['venueInfo']['name'] ?? 'International Stadium' }}</strong>
            <br>
            <i class="fa-solid fa-circle-info me-1"></i> Match Format: <strong class="text-uppercase text-danger">{{ $match['matchInfo']['matchFormat'] ?? 'Cricket Match' }}</strong>
        </div>
    </div>
</div>
@endsection