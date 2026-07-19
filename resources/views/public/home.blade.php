@extends('layouts.public')

@section('title', 'Live Match Feed - CRICKTRACKER-KUET')

@section('header_banner')
    <header class="hero-banner">
        <div class="container text-center">
            <h1 class="fw-800 mb-2">Live Match Center</h1>
            <p class="text-white-50 fs-6 mb-0">Track real-time score updates, current overs, and down-the-wire tournament action.</p>
        </div>
    </header>
@endsection

@section('content')
    <!-- Injecting styling for premium card hovering effects -->
    <style>
        .live-card-link {
            text-decoration: none !important;
            color: inherit !important;
            display: block;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .live-card-link:hover {
            transform: translateY(-4px);
        }
        .live-card-link:hover .cric-card {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            border-color: rgba(0, 123, 255, 0.2);
        }
    </style>

    <div class="row">
        <div class="col-12">
            <h2 class="section-title mb-4">Active Live Feeds</h2>
            <div class="row g-4">
                @forelse($liveMatches as $match)
                    @php
                        $score = $match->matchScore;
                        
                        // Determine the base team sequence from the toss or innings settings
                        $teamOneBatsFirst = true;
                        if (!empty($match->toss_winner_id) && !empty($match->toss_decision)) {
                            if ($match->toss_winner_id == $match->team_one_id) {
                                $teamOneBatsFirst = (strtolower($match->toss_decision) === 'bat');
                            } else {
                                $teamOneBatsFirst = (strtolower($match->toss_decision) === 'bowl');
                            }
                        }
                        
                        if (isset($score->innings_one_batting_team_id) && !empty($score->innings_one_batting_team_id)) {
                            $teamOneBatsFirst = ($score->innings_one_batting_team_id == $match->team_one_id);
                        }

                        // Parse execution rules based on current innings
                        $currentInnings = $score->current_innings ?? 1;

                        if ($currentInnings == 2) {
                            // Innings 1 data belongs to whoever batted first
                            $inns1Runs = $score->innings_one_runs ?? 0;
                            $inns1Wickets = $score->innings_one_wickets ?? 0;
                            $inns1Balls = $score->innings_one_balls ?? 0;
                            $inns1Overs = floor($inns1Balls / 6) . '.' . ($inns1Balls % 6);

                            // Innings 2 data belongs to the team chasing
                            $inns2Runs = $score->innings_two_runs ?? 0;
                            $inns2Wickets = $score->innings_two_wickets ?? 0;
                            $inns2Balls = $score->innings_two_balls ?? 0;
                            $inns2Overs = floor($inns2Balls / 6) . '.' . ($inns2Balls % 6);

                            if ($teamOneBatsFirst) {
                                $teamOneText = $inns1Runs . '/' . $inns1Wickets . ' (' . $inns1Overs . ' ov)';
                                $teamTwoText = $inns2Runs . '/' . $inns2Wickets . ' (' . $inns2Overs . ' ov)';
                                $teamOneActive = false;
                                $teamTwoActive = true;
                            } else {
                                $teamOneText = $inns2Runs . '/' . $inns2Wickets . ' (' . $inns2Overs . ' ov)';
                                $teamTwoText = $inns1Runs . '/' . $inns1Wickets . ' (' . $inns1Overs . ' ov)';
                                $teamOneActive = true;
                                $teamTwoActive = false;
                            }
                        } else {
                            // Innings 1 is active
                            $inns1Runs = $score->runs ?? 0;
                            $inns1Wickets = $score->wickets ?? 0;
                            $inns1Balls = $score->balls_bowled ?? 0;
                            $inns1Overs = floor($inns1Balls / 6) . '.' . ($inns1Balls % 6);

                            if ($teamOneBatsFirst) {
                                $teamOneText = $inns1Runs . '/' . $inns1Wickets . ' (' . $inns1Overs . ' ov)';
                                $teamTwoText = 'Yet to bat';
                                $teamOneActive = true;
                                $teamTwoActive = false;
                            } else {
                                $teamOneText = 'Yet to bat';
                                $teamTwoText = $inns1Runs . '/' . $inns1Wickets . ' (' . $inns1Overs . ' ov)';
                                $teamOneActive = false;
                                $teamTwoActive = true;
                            }
                        }
                    @endphp

                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="{{ route('public.matches.show', $match->id) }}" class="live-card-link">
                            <div class="cric-card p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-danger text-white px-2 py-1 uppercase small">
                                        <i class="fa-solid fa-circle small me-1"></i> LIVE NOW
                                    </span>
                                    <small class="text-muted">
                                        <i class="fa-solid fa-location-dot me-1"></i> {{ $match->venue }}
                                    </small>
                                </div>
                                
                                <div class="d-flex flex-column gap-2 my-3">
                                    <!-- Team One Lineup Display -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="{{ $teamOneActive ? 'fw-bold text-dark fs-5' : 'text-muted fs-5 fw-medium' }}">
                                            {{ $match->teamOne->name }}
                                            @if($teamOneActive)<span class="text-danger small fs-6 ms-1">🏏</span>@endif
                                        </span>
                                        <span class="{{ $teamOneActive ? 'fw-bold fs-5 text-dark' : 'fs-7 text-muted fw-medium' }}">
                                            {{ $teamOneText }}
                                        </span>
                                    </div>

                                    <!-- Team Two Lineup Display -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="{{ $teamTwoActive ? 'fw-bold text-dark fs-5' : 'text-muted fs-5 fw-medium' }}">
                                            {{ $match->teamTwo->name }}
                                            @if($teamTwoActive)<span class="text-danger small fs-6 ms-1">🏏</span>@endif
                                        </span>
                                        <span class="{{ $teamTwoActive ? 'fw-bold fs-5 text-dark' : 'fs-7 text-muted fw-medium' }}">
                                            {{ $teamTwoText }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="border-top pt-3 mt-2">
                                    <small class="text-danger fw-semibold">
                                        <i class="fa-solid fa-clock me-1"></i> Innings {{ $currentInnings }} coverage is active.
                                    </small>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="bg-white rounded-3 border p-5 text-center text-muted shadow-xs">
                            <i class="fa-solid fa-satellite-dish fs-1 text-muted mb-3 d-block"></i>
                            No active local match feeds are live right now. Active coverage dashboards appear automatically.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection