@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Tournament Fixtures & Games</h1>
    <a href="{{ route('games.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="fa-solid fa-calendar-plus me-1"></i> Schedule New Match
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4" style="width: 120px;">Match No.</th>
                        <th>Competing Teams</th>
                        <th>Date & Time</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th>Scores Summary</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($games as $game)
                        <tr>
                            <td class="ps-4 fw-bold text-secondary font-monospace">
                                {{ $game->match_number }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center fw-semibold text-dark">
                                    <span>{{ $game->team1->name }}</span>
                                    <span class="text-muted mx-2 small">vs</span>
                                    <span>{{ $game->team2->name }}</span>
                                </div>
                                @if($game->status === 'Completed' && $game->winner)
                                    <small class="text-success d-block mt-1">
                                        <i class="fa-solid fa-trophy me-1 text-warning"></i> Winner: <strong>{{ $game->winner->name }}</strong>
                                    </small>
                                @endif
                            </td>
                            <td>
                                <span class="d-block text-dark fw-medium">
                                    {{ $game->match_date->format('M d, Y') }}
                                </span>
                                <small class="text-muted font-monospace">
                                    {{ $game->match_date->format('h:i A') }}
                                </small>
                            </td>
                            <td>
                                <span class="text-secondary small fw-medium">
                                    <i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $game->venue }}
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-2.5 py-1.5 fs-8
                                    @if($game->status === 'Scheduled') bg-secondary
                                    @elseif($game->status === 'Live') bg-danger animate-pulse
                                    @elseif($game->status === 'Completed') bg-success
                                    @else bg-dark @endif">
                                    {{ $game->status }}
                                </span>
                            </td>
                            <td>
                                @if($game->status === 'Scheduled')
                                    <span class="text-muted small italic">Fixture Upcoming</span>
                                @elseif($game->status === 'Abandoned')
                                    <span class="text-muted line-through small">Match Called Off</span>
                                @else
                                    <div class="small">
                                        <div class="text-dark fw-medium">{{ $game->team1->name }}: <span class="font-monospace text-primary">{{ $game->team1_score ?? 'N/A' }}</span></div>
                                        <div class="text-dark fw-medium">{{ $game->team2->name }}: <span class="font-monospace text-primary">{{ $game->team2_score ?? 'N/A' }}</span></div>
                                    </div>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('games.show', $game->id) }}" class="btn btn-sm btn-outline-info me-1" title="View Match Card">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('games.edit', $game->id) }}" class="btn btn-sm btn-outline-secondary me-1" title="Update Scorecard">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('games.destroy', $game->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this match fixture?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Fixture">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-calendar-times fa-3x mb-3 text-secondary"></i>
                                <p class="mb-0">No match fixtures have been scheduled yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($games->hasPages())
        <div class="card-footer bg-white border-0 pt-0 pb-3 px-4">
            {{ $games->links() }}
        </div>
    @endif
</div>
@endsection