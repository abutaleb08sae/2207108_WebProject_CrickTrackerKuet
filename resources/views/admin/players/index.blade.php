@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Registered Players</h1>
    <a href="{{ route('players.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="fa-solid fa-user-plus me-1"></i> Register Player
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
                        <th class="ps-4" style="width: 70px;">Photo</th>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Team</th>
                        <th>Role</th>
                        <th>Matches</th>
                        <th>Runs</th>
                        <th>Wickets</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($players as $player)
                        <tr>
                            <td class="ps-4">
                                @if($player->photo_path)
                                    <img src="{{ asset('storage/' . $player->photo_path) }}" alt="{{ $player->name }}" class="rounded-circle border" style="width: 38px; height: 38px; object-fit: cover;">
                                @elseif($player->image_path)
                                    <img src="{{ asset('storage/' . $player->image_path) }}" alt="{{ $player->name }}" class="rounded-circle border" style="width: 38px; height: 38px; object-fit: cover;">
                                @else
                                    <div class="bg-light text-secondary rounded-circle border d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 13px;">
                                        {{ substr($player->name, 0, 2) }}
                                    </div>
                                @endif
                            </td>
                            <td class="fw-bold text-secondary font-monospace">{{ $player->student_id }}</td>
                            <td>
                                <a href="{{ route('players.show', $player->id) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ $player->name }}
                                </a>
                                @if($player->jersey_number)
                                    <span class="badge bg-light text-dark border ms-1">#{{ $player->jersey_number }}</span>
                                @endif
                            </td>
                            <td>
                                @if($player->team)
                                    <span class="badge bg-secondary">{{ $player->team->name }}</span>
                                @else
                                    <span class="text-muted small">No Team</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge rounded-pill 
                                    @if($player->role == 'Batsman') bg-success 
                                    @elseif($player->role == 'Bowler') bg-danger 
                                    @elseif($player->role == 'All-rounder') bg-warning text-dark 
                                    @else bg-info text-dark @endif">
                                    {{ $player->role }}
                                </span>
                            </td>
                            <td class="fw-bold">{{ $player->matches_played }}</td>
                            <td class="text-success fw-bold">{{ $player->total_runs }}</td>
                            <td class="text-danger fw-bold">
                                {{ $player->wickets_taken ?? $player->total_wickets ?? 0 }}
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('players.show', $player->id) }}" class="btn btn-sm btn-outline-info me-1" title="View Profile">
                                    <i class="fa-solid fa-id-card"></i>
                                </a>
                                <a href="{{ route('players.edit', $player->id) }}" class="btn btn-sm btn-outline-secondary me-1" title="Edit Profile">
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                                <form action="{{ route('players.destroy', $player->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this player from the roster?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Profile">
                                        <i class="fa-solid fa-user-xmark"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-users-slash fa-3x mb-3 text-secondary"></i>
                                <p class="mb-0">No players registered yet. Add teams first, then add players.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection