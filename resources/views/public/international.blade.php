@extends('layouts.public')

@section('content')
<style>
    .cricket-dashboard-wrapper {
        background-color: #f8fafc;
        padding: 40px 20px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        color: #1e293b;
        text-align: left;
    }
    .cricket-container {
        max-w: 1200px;
        margin: 0 auto;
    }
    .dashboard-header {
        background: #ffffff;
        border-top: 4px solid #0284c7;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 32px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    .header-left h1 {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.5px;
    }
    .header-left p {
        color: #64748b;
        font-size: 14px;
        margin: 6px 0 0 0;
        font-weight: 500;
    }
    .live-badge {
        background-color: #fef2f2;
        color: #dc2626;
        border: 1px solid #fee2e2;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 12px;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .live-pulse-dot {
        width: 8px;
        height: 8px;
        background-color: #dc2626;
        border-radius: 50%;
        display: inline-block;
        animation: pulse-animation 1.5s infinite;
    }
    @keyframes pulse-animation {
        0% { opacity: 0.3; }
        50% { opacity: 1; }
        100% { opacity: 0.3; }
    }
    .section-heading {
        font-size: 20px;
        font-weight: 800;
        color: #1e293b;
        margin: 32px 0 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .heading-bar {
        width: 4px;
        height: 22px;
        border-radius: 4px;
        display: inline-block;
    }
    .match-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 24px;
    }
    .match-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .match-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .card-header {
        background: #f8fafc;
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .series-title {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 75%;
    }
    .format-badge {
        background: #e2e8f0;
        color: #475569;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 800;
    }
    .card-body {
        padding: 20px;
    }
    .team-score-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
    }
    .team-score-row:last-child {
        margin-bottom: 0;
    }
    .team-name {
        font-size: 16px;
        font-weight: 700;
        color: #334155;
    }
    .score-display {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        font-family: monospace, sans-serif;
    }
    .not-started {
        font-size: 12px;
        color: #94a3b8;
        font-weight: 600;
        font-family: sans-serif;
    }
    .card-footer {
        padding: 12px 18px;
        font-size: 12px;
        font-weight: 700;
        border-top: 1px solid #f1f5f9;
    }
    .footer-live {
        background-color: #fff5f5;
        color: #e53e3e;
    }
    .footer-recent {
        background-color: #f0f9ff;
        color: #0369a1;
    }
    .footer-upcoming {
        background-color: #f8fafc;
        color: #475569;
    }
    .no-data-card {
        background: #ffffff;
        padding: 48px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        text-align: center;
        color: #64748b;
        font-weight: 600;
        font-size: 15px;
    }
</style>

<div class="cricket-dashboard-wrapper">
    <div class="cricket-container">
        
        <div class="dashboard-header">
            <div class="header-left">
                <h1>International Match Center</h1>
                <p class="header-subtitle">Real-time international tournament coverage, live feeds, and fixtures.</p>
            </div>
            <div class="live-badge">
                <span class="live-pulse-dot"></span> LIVE ENGINE ACTIVE
            </div>
        </div>

        <div style="margin-bottom: 40px;">
            <h2 class="section-heading">
                <span class="heading-bar" style="background-color: #dc2626;"></span> Live Matches
            </h2>
            
            @if(count($liveSchedules) > 0)
                <div class="match-grid">
                    @foreach($liveSchedules as $match)
                        <div class="match-card">
                            <div class="card-header">
                                <span class="series-title" title="{{ $match['seriesName'] }}">{{ $match['seriesName'] }}</span>
                                <span class="format-badge">{{ $match['matchInfo']['matchFormat'] ?? 'T20' }}</span>
                            </div>
                            <div class="card-body">
                                <div class="team-score-row">
                                    <span class="team-name">{{ $match['matchInfo']['team1']['teamName'] ?? 'Team 1' }}</span>
                                    <span class="score-display">
                                        @if(isset($match['matchScore']['team1Score']['inngs1']['runs']))
                                            {{ $match['matchScore']['team1Score']['inngs1']['runs'] }}-{{ $match['matchScore']['team1Score']['inngs1']['wickets'] ?? '0' }}
                                        @else
                                            <span class="not-started">Yet to bat</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="team-score-row">
                                    <span class="team-name">{{ $match['matchInfo']['team2']['teamName'] ?? 'Team 2' }}</span>
                                    <span class="score-display">
                                        @if(isset($match['matchScore']['team2Score']['inngs1']['runs']))
                                            {{ $match['matchScore']['team2Score']['inngs1']['runs'] }}-{{ $match['matchScore']['team2Score']['inngs1']['wickets'] ?? '0' }}
                                        @else
                                            <span class="not-started">Yet to bat</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer footer-live">
                                🏏 {{ $match['matchInfo']['status'] ?? 'Match in progress' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-data-card">
                    No active international live matches currently on stream.
                </div>
            @endif
        </div>

        <div style="margin-bottom: 40px;">
            <h2 class="section-heading">
                <span class="heading-bar" style="background-color: #0284c7;"></span> Recent Results
            </h2>
            
            @if(count($recentSchedules) > 0)
                <div class="match-grid">
                    @foreach($recentSchedules as $match)
                        <div class="match-card">
                            <div class="card-header">
                                <span class="series-title" title="{{ $match['seriesName'] }}">{{ $match['seriesName'] }}</span>
                                <span class="format-badge" style="background:#475569; color:#ffffff;">FINISHED</span>
                            </div>
                            <div class="card-body">
                                <div class="team-score-row" style="margin-bottom: 8px;">
                                    <span class="team-name" style="color: #475569;">{{ $match['matchInfo']['team1']['teamName'] ?? 'Team 1' }}</span>
                                </div>
                                <div class="team-score-row">
                                    <span class="team-name" style="color: #475569;">{{ $match['matchInfo']['team2']['teamName'] ?? 'Team 2' }}</span>
                                </div>
                            </div>
                            <div class="card-footer footer-recent">
                                🏆 {{ $match['matchInfo']['status'] ?? 'Match complete' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-data-card">
                    No recent international results available at the moment.
                </div>
            @endif
        </div>

        <div>
            <h2 class="section-heading">
                <span class="heading-bar" style="background-color: #8b5cf6;"></span> Upcoming Fixtures
            </h2>
            
            @if(count($upcomingSchedules) > 0)
                <div class="match-grid">
                    @foreach($upcomingSchedules as $match)
                        <div class="match-card">
                            <div class="card-header">
                                <span class="series-title" title="{{ $match['seriesName'] }}">{{ $match['seriesName'] }}</span>
                                <span class="format-badge" style="background:#e0e7ff; color:#4338ca;">{{ $match['matchInfo']['matchFormat'] ?? 'UPCOMING' }}</span>
                            </div>
                            <div class="card-body">
                                <div class="team-score-row" style="margin-bottom: 8px;">
                                    <span class="team-name">{{ $match['matchInfo']['team1']['teamName'] ?? 'Team A' }}</span>
                                </div>
                                <div class="team-score-row" style="margin-bottom: 8px;">
                                    <span class="team-name" style="font-size: 13px; color:#94a3b8; font-weight: 500;">versus</span>
                                </div>
                                <div class="team-score-row">
                                    <span class="team-name">{{ $match['matchInfo']['team2']['teamName'] ?? 'Team B' }}</span>
                                </div>
                            </div>
                            <div class="card-footer footer-upcoming">
                                📍 {{ $match['matchInfo']['venueInfo']['ground'] ?? 'International Stadium' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-data-card">
                    No upcoming matches listed.
                </div>
            @endif
        </div>

    </div>
</div>
@endsection