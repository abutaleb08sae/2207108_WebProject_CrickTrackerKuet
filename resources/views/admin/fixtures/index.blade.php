@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Match Fixtures</h1>
    <a href="{{ route('fixtures.create') }}" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-calendar-plus me-1"></i> Schedule Match</a>
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
                        <th class="ps-4">Match Matchup</th>
                        <th>Date & Time</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fixtures as $fixture)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="mb-1">
                                    <span class="fw-bold text-dark">{{ $fixture->teamOne->name }}</span> 
                                    <span class="text-muted px-2 fw-normal">vs</span> 
                                    <span class="fw-bold text-dark">{{ $fixture->teamTwo->name }}</span>
                                </div>
                                <!-- Tournament Type UI Label Pill Element -->
                                <span class="badge bg-light text-dark border" style="font-size: 11px; font-weight: 600; padding: 4px 8px;">
                                    🏆 {{ $fixture->tournament_type ?? 'Inter Department Cricket Tournament' }}
                                </span>
                            </td>
                            <td>{{ date('M d, Y - h:i A', strtotime($fixture->match_datetime)) }}</td>
                            <td><small class="text-dark"><i class="fa-solid fa-location-dot text-secondary me-1"></i>{{ $fixture->venue }}</small></td>
                            <td>
                                @if($fixture->status == 'Upcoming')
                                    <span class="badge bg-primary">Upcoming</span>
                                @elseif($fixture->status == 'Live')
                                    <span class="badge bg-danger animate-pulse"><i class="fa-solid fa-circle text-white me-1 small"></i>LIVE</span>
                                @else
                                    <span class="badge bg-secondary">Completed</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('fixtures.edit', $fixture->id) }}" class="btn btn-sm btn-outline-secondary me-1"><i class="fa-solid fa-calendar-days"></i></a>
                                <form action="{{ route('fixtures.destroy', $fixture->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Cancel this match listing?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-calendar-xmark"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-calendar-minus fa-3x mb-3 text-secondary"></i>
                                <p class="mb-0">No matches scheduled yet. Tap "Schedule Match" to deploy a fixture card.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection