@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Registered Players</h1>
    <a href="{{ route('players.create') }}" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-user-plus me-1"></i> Register Player</a>
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
                        <th class="ps-4">Student ID</th>
                        <th>Name</th>
                        <th>Team</th>
                        <th>Role</th>
                        <th>Matches</th>
                        <th>Runs</th>
                        <th>Wickets</th>
                        <th class="text-end pr-4 pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($players as $player)
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">{{ $player->student_id }}</td>
                            <td class="fw-bold text-dark">{{ $player->name }}</td>
                            <td><span class="badge bg-secondary">{{ $player->team->name }}</span></td>
                            <td>{{ $player->role }}</td>
                            <td>{{ $player->matches_played }}</td>
                            <td class="text-success fw-bold">{{ $player->total_runs }}</td>
                            <td class="text-danger fw-bold">{{ $player->total_wickets }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('players.edit', $player->id) }}" class="btn btn-sm btn-outline-secondary me-1"><i class="fa-solid fa-user-pen"></i></a>
                                <form action="{{ route('players.destroy', $player->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this player from the roster?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-user-xmark"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
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