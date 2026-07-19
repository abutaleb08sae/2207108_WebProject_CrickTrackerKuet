@extends('layouts.public')

@section('content')
<style>
    .cricket-dashboard-wrapper {
        background-color: #f1f5f9;
        padding: 48px 20px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #0f172a;
        text-align: left;
    }
    .cricket-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    .dashboard-header {
        background: #ffffff;
        border-top: 4px solid #0284c7;
        border-radius: 12px;
        padding: 28px 32px;
        margin-bottom: 40px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    .header-left h1 {
        font-size: 32px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.75px;
    }
    .header-subtitle {
        color: #64748b;
        font-size: 15px;
        margin: 6px 0 0 0;
        font-weight: 500;
    }
    .live-badge {
        background-color: #fef2f2;
        color: #dc2626;
        border: 1px solid #fee2e2;
        padding: 10px 20px;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 12px;
        letter-spacing: 0.75px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .live-pulse-dot {
        width: 10px;
        height: 10px;
        background-color: #dc2626;
        border-radius: 50%;
        animation: pulse-animation 1.8s infinite;
    }
    @keyframes pulse-animation {
        0% { opacity: 0.4; transform: scale(0.95); }
        50% { opacity: 1; transform: scale(1.1); }
        100% { opacity: 0.4; transform: scale(0.95); }
    }
    .section-heading {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin: 48px 0 24px 0;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .heading-bar {
        width: 6px;
        height: 26px;
        border-radius: 99px;
    }
    .match-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 28px;
    }
    .match-link-wrapper {
        text-decoration: none !important;
        display: block;
    }
    .match-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .match-link-wrapper:hover .match-card {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #cbd5e1;
    }
    .card-header {
        background: #f8fafc;
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .series-title {
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 75%;
    }
    .format-badge {
        background: #e2e8f0;
        color: #334155;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 800;
    }
    .card-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 18px;
        background: #ffffff;
    }
    .team-score-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .team-name {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
    }
    .score-display {
        font-size: 19px;
        font-weight: 800;
        color: #0f172a;
        font-family: monospace;
    }
    .overs-display {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        margin-left: 4px;
    }
    .card-footer {
        padding: 16px 24px;
        font-size: 14px;
        font-weight: 700;
        border-top: 1px solid #f1f5f9;
        margin-top: auto;
    }
    .footer-live { background-color: #fff5f5; color: #dc2626; }
    .footer-recent { background-color: #f0f9ff; color: #0284c7; }
    .footer-upcoming { background-color: #f8fafc; color: #475569; }
    
    .no-data-card {
        background: #ffffff;
        padding: 40px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        text-align: center;
        grid-column: 1 / -1;
    }
    .no-data-icon { font-size: 36px; margin-bottom: 12px; display: block; }
    .no-data-title { font-size: 16px; font-weight: 700; color: #334155; }
    .no-data-desc { color: #64748b; font-size: 13px; margin-top: 4px; }

    /* Beautiful CSS Skeleton loading spaces */
    .skeleton-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        height: 190px;
        position: relative;
        overflow: hidden;
    }
    .skeleton-card::after {
        content: "";
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        transform: translateX(-100%);
        background-image: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.5) 20%, rgba(255,255,255,0.7) 60%, rgba(255,255,255,0) 100%);
        animation: shimmer-effect 1.6s infinite;
    }
    @keyframes shimmer-effect {
        100% { transform: translateX(100%); }
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

        <!-- Live Matches Block -->
        <div style="margin-bottom: 48px;">
            <h2 class="section-heading">
                <span class="heading-bar" style="background-color: #dc2626;"></span> Live Matches
            </h2>
            <div id="live-container" class="match-grid">
                <div class="skeleton-card"></div>
                <div class="skeleton-card"></div>
            </div>
        </div>

        <!-- Recent Results Block -->
        <div style="margin-bottom: 48px;">
            <h2 class="section-heading">
                <span class="heading-bar" style="background-color: #0284c7;"></span> Recent Results
            </h2>
            <div id="recent-container" class="match-grid">
                <div class="skeleton-card"></div>
                <div class="skeleton-card"></div>
            </div>
        </div>

        <!-- Upcoming Fixtures Block -->
        <div>
            <h2 class="section-heading">
                <span class="heading-bar" style="background-color: #8b5cf6;"></span> Upcoming Fixtures
            </h2>
            <div id="upcoming-container" class="match-grid">
                <div class="skeleton-card"></div>
                <div class="skeleton-card"></div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Standard Asynchronous Fetch Execution Requirement
    fetch("{{ url('/api/international-matches-data') }}")
        .then(response => {
            if (!response.ok) throw new Error("API Channel Offline");
            return response.json();
        })
        .then(data => {
            renderLiveSection(data.live);
            renderRecentSection(data.recent);
            renderUpcomingSection(data.upcoming);
        })
        .catch(error => {
            console.error("AJAX Fetch Error Context:", error);
            const errHtml = `
                <div class="no-data-card">
                    <span class="no-data-icon">⚠️</span>
                    <div class="no-data-title">Synchronization Broken</div>
                    <div class="no-data-desc">Failed to connect to backend feed streams. Please hit refresh.</div>
                </div>`;
            document.getElementById('live-container').innerHTML = errHtml;
            document.getElementById('recent-container').innerHTML = errHtml;
            document.getElementById('upcoming-container').innerHTML = errHtml;
        });
});

function renderLiveSection(matches) {
    const container = document.getElementById('live-container');
    if (!matches || matches.length === 0) {
        container.innerHTML = `
            <div class="no-data-card">
                <span class="no-data-icon">🏏</span>
                <div class="no-data-title">No Active Live Matches</div>
                <div class="no-data-desc">There are no live international cricket matches tracking right now.</div>
            </div>`;
        return;
    }

    let html = '';
    matches.forEach(match => {
        let t1Score = 'Yet to bat', t2Score = 'Yet to bat';
        
        if (match.matchScore?.team1Score?.inngs1?.runs !== undefined) {
            let overs = match.matchScore.team1Score.inngs1.overs ? `<span class="overs-display">(${match.matchScore.team1Score.inngs1.overs})</span>` : '';
            t1Score = `${match.matchScore.team1Score.inngs1.runs}-${match.matchScore.team1Score.inngs1.wickets || 0}${overs}`;
        } else if (match.matchScore?.team1Score?.runs !== undefined) {
            t1Score = `${match.matchScore.team1Score.runs}-${match.matchScore.team1Score.wickets || 0}`;
        }

        if (match.matchScore?.team2Score?.inngs1?.runs !== undefined) {
            let overs = match.matchScore.team2Score.inngs1.overs ? `<span class="overs-display">(${match.matchScore.team2Score.inngs1.overs})</span>` : '';
            t2Score = `${match.matchScore.team2Score.inngs1.runs}-${match.matchScore.team2Score.inngs1.wickets || 0}${overs}`;
        } else if (match.matchScore?.team2Score?.runs !== undefined) {
            t2Score = `${match.matchScore.team2Score.runs}-${match.matchScore.team2Score.wickets || 0}`;
        }

        html += `
            <a href="{{ url('matches') }}/${match.matchId || '#'}" class="match-link-wrapper">
                <div class="match-card">
                    <div class="card-header">
                        <span class="series-title" title="${match.seriesName}">${match.seriesName}</span>
                        <span class="format-badge" style="background: #fee2e2; color: #dc2626;">${match.matchInfo?.matchFormat || 'LIVE'}</span>
                    </div>
                    <div class="card-body">
                        <div class="team-score-row">
                            <span class="team-name">${match.matchInfo?.team1?.teamName || 'Team 1'}</span>
                            <span class="score-display">${t1Score}</span>
                        </div>
                        <div class="team-score-row">
                            <span class="team-name">${match.matchInfo?.team2?.teamName || 'Team 2'}</span>
                            <span class="score-display">${t2Score}</span>
                        </div>
                    </div>
                    <div class="card-footer footer-live">
                        🔴 ${match.matchInfo?.status || 'Match in progress'}
                    </div>
                </div>
            </a>`;
    });
    container.innerHTML = html;
}

function renderRecentSection(matches) {
    const container = document.getElementById('recent-container');
    if (!matches || matches.length === 0) {
        container.innerHTML = `
            <div class="no-data-card">
                <span class="no-data-icon">📊</span>
                <div class="no-data-title">No Recent Matches</div>
                <div class="no-data-desc">No recently concluded international scorecards found.</div>
            </div>`;
        return;
    }

    let html = '';
    matches.forEach(match => {
        let t1Runs = match.matchScore?.team1Score?.inngs1?.runs !== undefined ? `${match.matchScore.team1Score.inngs1.runs}-${match.matchScore.team1Score.inngs1.wickets || 0}` : '';
        let t2Runs = match.matchScore?.team2Score?.inngs1?.runs !== undefined ? `${match.matchScore.team2Score.inngs1.runs}-${match.matchScore.team2Score.inngs1.wickets || 0}` : '';

        html += `
            <a href="{{ url('matches') }}/${match.matchId || '#'}" class="match-link-wrapper">
                <div class="match-card">
                    <div class="card-header">
                        <span class="series-title" title="${match.seriesName}">${match.seriesName}</span>
                        <span class="format-badge" style="background:#f1f5f9; color:#475569;">FINISHED</span>
                    </div>
                    <div class="card-body">
                        <div class="team-score-row">
                            <span class="team-name" style="color: #475569;">${match.matchInfo?.team1?.teamName || 'Team 1'}</span>
                            <span class="score-display" style="color: #64748b;">${t1Runs}</span>
                        </div>
                        <div class="team-score-row">
                            <span class="team-name" style="color: #475569;">${match.matchInfo?.team2?.teamName || 'Team 2'}</span>
                            <span class="score-display" style="color: #64748b;">${t2Runs}</span>
                        </div>
                    </div>
                    <div class="card-footer footer-recent">
                        🏆 ${match.matchInfo?.status || 'Match complete'}
                    </div>
                </div>
            </a>`;
    });
    container.innerHTML = html;
}

function renderUpcomingSection(matches) {
    const container = document.getElementById('upcoming-container');
    if (!matches || matches.length === 0) {
        container.innerHTML = `
            <div class="no-data-card">
                <span class="no-data-icon">📅</span>
                <div class="no-data-title">No Upcoming Matches</div>
                <div class="no-data-desc">There are no upcoming international fixtures scheduled.</div>
            </div>`;
        return;
    }

    let html = '';
    matches.forEach(match => {
        let ground = match.matchInfo?.venueInfo?.ground || match.matchInfo?.venueInfo?.name || 'International Stadium';
        html += `
            <a href="{{ url('matches') }}/${match.matchId || '#'}" class="match-link-wrapper">
                <div class="match-card">
                    <div class="card-header">
                        <span class="series-title" title="${match.seriesName}">${match.seriesName}</span>
                        <span class="format-badge" style="background:#f3e8ff; color:#6b21a8;">${match.matchInfo?.matchFormat || 'UPCOMING'}</span>
                    </div>
                    <div class="card-body" style="gap: 12px;">
                        <div class="team-score-row">
                            <span class="team-name" style="font-size: 17px;">${match.matchInfo?.team1?.teamName || 'Team A'}</span>
                        </div>
                        <div style="color:#94a3b8; font-weight: 700; font-size: 11px; letter-spacing: 1px;">VS</div>
                        <div class="team-score-row">
                            <span class="team-name" style="font-size: 17px;">${match.matchInfo?.team2?.teamName || 'Team B'}</span>
                        </div>
                    </div>
                    <div class="card-footer footer-upcoming">
                        📍 ${ground}
                    </div>
                </div>
            </a>`;
    });
    container.innerHTML = html;
}
</script>
@endsection