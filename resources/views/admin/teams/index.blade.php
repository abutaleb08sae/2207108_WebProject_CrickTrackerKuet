@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">KUET Teams</h1>
    <a href="{{ route('teams.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="fa-solid fa-plus me-1"></i> Add New Team
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
                        <th class="ps-4" style="width: 80px;">Logo</th>
                        <th>Team Name</th>
                        <th>Short Name</th>
                        <th>Country</th>
                        <th>Ranking</th>
                        <th>Coach</th>
                        <th>Captain</th>
                        <th>History/Description</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teams as $team)
                        <tr>
                            <td class="ps-4">
                                @if($team->logo_path)
                                    <img src="{{ asset('storage/' . $team->logo_path) }}" alt="{{ $team->name }}" class="rounded border" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center border" style="width: 40px; height: 40px; font-weight: bold; font-size: 14px;">
                                        {{ $team->short_name ?? substr($team->name, 0, 2) }}
                                    </div>
                                @endif
                            </td>
                            <td><span class="fw-bold text-dark">{{ $team->name }}</span></td>
                            <td><span class="badge bg-light text-dark font-monospace border">{{ $team->short_name ?? 'N/A' }}</span></td>
                            <td>{{ $team->country ?? 'N/A' }}</td>
                            <td>
                                @if($team->ranking)
                                    <span class="badge bg-info text-dark">#{{ $team->ranking }}</span>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            <td>{{ $team->coach ?? 'TBD' }}</td>
                            <td>
                                @if($team->captain)
                                    <span class="text-dark small fw-semibold">👤 {{ $team->captain->name }}</span>
                                @else
                                    <span class="text-muted small">None Assigned</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-muted text-truncate" style="max-width: 200px;" title="{{ $team->club_history ?? $team->description }}">
                                    {{ $team->club_history ?? $team->description ?? 'No description provided.' }}
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('teams.edit', $team->id) }}" class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('teams.destroy', $team->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this team and all its registered players?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open fa-3x mb-3 text-secondary"></i>
                                <p class="mb-0">No teams found. Click "Add New Team" to populate the records.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection