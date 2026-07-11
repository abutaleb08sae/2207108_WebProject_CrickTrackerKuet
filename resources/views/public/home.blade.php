<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRICKTRACKER-KUET | Inter-Department Match Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f1f3f4;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #212529;
        }
        .navbar-brand {
            font-size: 1.15rem;
            letter-spacing: 0.5px;
        }
        .match-section-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #5f6368;
            font-weight: 700;
        }
        .cric-card {
            background: #fff;
            border-radius: 8px;
            border: 1px solid #dadce0;
            transition: box-shadow 0.2s ease-in-out;
            min-height: 175px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .cric-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .match-header {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-live {
            color: #d93025;
        }
        .status-upcoming {
            color: #1a73e8;
        }
        .status-result {
            color: #5f6368;
        }
        .team-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
        }
        .team-name {
            font-size: 0.95rem;
            font-weight: 600;
            color: #202124;
        }
        .team-score {
            font-size: 0.95rem;
            font-weight: 700;
            color: #202124;
        }
        .overs-count {
            font-size: 0.75rem;
            color: #5f6368;
            font-weight: 400;
            margin-right: 4px;
        }
        .match-footer-text {
            font-size: 0.8rem;
            color: #5f6368;
        }
        .match-action-link {
            font-size: 0.8rem;
            text-decoration: none;
            color: #1a73e8;
            font-weight: 500;
            border-top: 1px solid #f1f3f4;
            padding-top: 8px;
            margin-top: 8px;
            display: block;
        }
        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: #d93025;
            border-radius: 50%;
            display: inline-block;
            margin-right: 4px;
            animation: cricPulse 1.5s infinite;
        }
        @keyframes cricPulse {
            0% { transform: scale(0.9); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.5; }
            100% { transform: scale(0.9); opacity: 1; }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-2">
        <div class="container">
            <a class="navbar-brand fw-bold text-info" href="{{ route('public.home') }}">
                <i class="fa-solid fa-circle-nodes me-2"></i>CRICKTRACKER-KUET
            </a>
            <a href="{{ url('/admin') }}" class="btn btn-outline-light btn-sm fw-bold px-3">
                <i class="fa-solid fa-lock me-1 small"></i> Admin Deck
            </a>
        </div>
    </nav>

    <main class="container my-4">
        <div class="mb-4">
            <h2 class="match-section-title mb-3"><span class="pulse-dot"></span>Live Matches</h2>
            <div class="row g-3">
                @forelse($liveMatches as $match)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="cric-card p-3">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="match-header status-live"><i class="fa-solid fa-circle small me-1"></i> Live</span>
                                    <span class="text-muted small">{{ $match->venue }}</span>
                                </div>
                                <div class="team-row mt-2">
                                    <span class="team-name">{{ $match->teamOne->name }}</span>
                                    <span class="team-score">
                                        {{ $match->matchScore->runs ?? 0 }}/{{ $match->matchScore->wickets ?? 0 }}
                                        <span class="overs-count">({{ floor(($match->matchScore->balls_bowled ?? 0) / 6) }}.{{ ($match->matchScore->balls_bowled ?? 0) % 6 }} ov)</span>
                                    </span>
                                </div>
                                <div class="team-row">
                                    <span class="team-name">{{ $match->teamTwo->name }}</span>
                                    <span class="team-score text-muted fs-6 fw-normal">Yet to bat</span>
                                </div>
                            </div>
                            <div>
                                <div class="match-footer-text text-truncate mt-2 text-danger fw-500">
                                    Innings 1 is currently in progress.
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="bg-white rounded border p-4 text-center text-muted small shadow-sm">
                            No matches are live at the moment. Active feeds appear automatically when coverage starts.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-12 col-md-6">
                <h2 class="match-section-title mb-3">Upcoming Schedules</h2>
                <div class="row g-3">
                    @forelse($upcomingMatches as $match)
                        <div class="col-12">
                            <div class="cric-card p-3" style="min-height: auto;">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="match-header status-upcoming">Upcoming</span>
                                        <span class="text-muted small">{{ date('M d, h:i A', strtotime($match->match_datetime)) }}</span>
                                    </div>
                                    <div class="team-row">
                                        <span class="team-name">{{ $match->teamOne->name }}</span>
                                    </div>
                                    <div class="team-row">
                                        <span class="team-name">{{ $match->teamTwo->name }}</span>
                                    </div>
                                </div>
                                <div class="match-action-link">
                                    <i class="fa-solid fa-location-dot me-1 text-muted"></i> Venue: {{ $match->venue }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="bg-white rounded border p-3 text-center text-muted small">No fixture matches scheduled.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="col-12 col-md-6">
                <h2 class="match-section-title mb-3">Recent Results</h2>
                <div class="row g-3">
                    @forelse($completedMatches as $match)
                        <div class="col-12">
                            <div class="cric-card p-3" style="min-height: auto;">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="match-header status-result">Result</span>
                                        <span class="text-muted small">{{ $match->venue }}</span>
                                    </div>
                                    <div class="team-row">
                                        <span class="team-name text-dark fw-bold">{{ $match->teamOne->slug }}</span>
                                        <span class="team-score">{{ $match->matchScore->runs ?? 0 }}/{{ $match->matchScore->wickets ?? 0 }}</span>
                                    </div>
                                    <div class="team-row">
                                        <span class="team-name text-muted">{{ $match->teamTwo->slug }}</span>
                                        <span class="team-score text-muted fw-normal fs-6">Target chased</span>
                                    </div>
                                </div>
                                <div class="match-action-link text-success fw-bold">
                                    Match complete • Total Overs: {{ floor(($match->matchScore->balls_bowled ?? 0) / 6) }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="bg-white rounded border p-3 text-center text-muted small">No match results archived yet.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white text-center py-3 mt-5 border-top border-secondary-subtle">
        <p class="mb-0 small text-muted">© 2026 CRICKTRACKER-KUET. Inter-Department Athletic Record Index.</p>
    </footer>

</body>
</html>