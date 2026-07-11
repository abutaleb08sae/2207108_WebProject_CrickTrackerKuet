@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">KUET Teams</h1>
    <a href="{{ route('teams.create') }}" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-plus me-1"></i> Add New Team</a>
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
                        <th class="ps-4">ID</th>
                        <th>Team Name</th>
                        <th>History/Description</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teams as $team)
                        <tr>
                            <td class="ps-4 text-muted fw-bold">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-dark">{{ $team->name }}</td>
                            <td class="text-muted text-truncate" style="max-width: 300px;">{{ $team->club_history ?? 'No description provided.' }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('teams.edit', $team->id) }}" class="btn btn-sm btn-outline-secondary me-1"><i class="fa-solid fa-pen-to-square"></i></a>
                                <form action="{{ route('teams.destroy', $team->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this team and all its registered players?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
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