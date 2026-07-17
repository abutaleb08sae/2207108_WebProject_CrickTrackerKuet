@extends('layouts.admin')

@section('admin-content')
<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Register New Player</h1>
</div>

<div class="mb-4">
    <a href="{{ route('players.index') }}" class="text-decoration-none fw-bold text-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Roster Directory
    </a>
</div>

<div class="card border-0 shadow-sm mb-5" style="max-width: 850px;">
    <div class="card-body p-4">
        <form action="{{ route('players.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <h5 class="fw-bold text-primary mb-3">
                <i class="fa-solid fa-id-card me-2"></i>Profile & Identity Details
            </h5>
            <div class="row g-3 mb-4">
                <!-- Full Name -->
                <div class="col-md-6">
                    <label for="name" class="form-label fw-bold">Full Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Student ID -->
                <div class="col-md-6">
                    <label for="student_id" class="form-label fw-bold">Student ID *</label>
                    <input type="text" class="form-control @error('student_id') is-invalid @enderror" id="student_id" name="student_id" value="{{ old('student_id') }}" placeholder="e.g., 2001001" required>
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Assigned Squad / Team -->
                <div class="col-md-6">
                    <label for="team_id" class="form-label fw-bold">Assigned Squad / Team *</label>
                    <select class="form-select @error('team_id') is-invalid @enderror" id="team_id" name="team_id" required>
                        <option value="">-- Choose Team --</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                        @endforeach
                    </select>
                    @error('team_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Player Role -->
                <div class="col-md-6">
                    <label for="role" class="form-label fw-bold">Player Role *</label>
                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                        <option value="">-- Choose Specialization --</option>
                        <option value="Batsman" {{ old('role') == 'Batsman' ? 'selected' : '' }}>Batsman</option>
                        <option value="Bowler" {{ old('role') == 'Bowler' ? 'selected' : '' }}>Bowler</option>
                        <option value="All-rounder" {{ old('role') == 'All-rounder' ? 'selected' : '' }}>All-rounder</option>
                        <option value="Wicketkeeper" {{ old('role') == 'Wicketkeeper' ? 'selected' : '' }}>Wicketkeeper</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h5 class="fw-bold text-dark mb-3">
                <i class="fa-solid fa-sliders me-2"></i>Bio Specs & Media
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="jersey_number" class="form-label fw-bold">Jersey Number</label>
                    <input type="text" class="form-control @error('jersey_number') is-invalid @enderror" id="jersey_number" name="jersey_number" value="{{ old('jersey_number') }}" placeholder="e.g., 45">
                </div>
                <div class="col-md-4">
                    <label for="batting_style" class="form-label fw-bold">Batting Style</label>
                    <input type="text" class="form-control @error('batting_style') is-invalid @enderror" id="batting_style" name="batting_style" value="{{ old('batting_style') }}" placeholder="Right-hand bat">
                </div>
                <div class="col-md-4">
                    <label for="bowling_style" class="form-label fw-bold">Bowling Style</label>
                    <input type="text" class="form-control @error('bowling_style') is-invalid @enderror" id="bowling_style" name="bowling_style" value="{{ old('bowling_style') }}" placeholder="Right-arm fast">
                </div>
                <div class="col-md-6">
                    <label for="date_of_birth" class="form-label fw-bold">Date of Birth</label>
                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                </div>
                <div class="col-md-6">
                    <label for="photo" class="form-label fw-bold">Profile Headshot Photo</label>
                    <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo">
                    <small class="text-muted d-block mt-1">Acceptable limits: JPG, PNG, GIF (Max 2MB)</small>
                </div>
            </div>

            <h5 class="fw-bold text-success mb-3">
                <i class="fa-solid fa-chart-line me-2"></i>Performance Statistics Registry
            </h5>
            <div class="row g-3 mb-4 bg-light p-3 rounded border">
                <div class="col-md-4">
                    <label for="matches_played" class="form-label fw-bold">Matches Played *</label>
                    <input type="number" class="form-control @error('matches_played') is-invalid @enderror" id="matches_played" name="matches_played" value="{{ old('matches_played', 0) }}" min="0" required>
                    @error('matches_played') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="total_runs" class="form-label fw-bold text-success">Total Runs *</label>
                    <input type="number" class="form-control @error('total_runs') is-invalid @enderror" id="total_runs" name="total_runs" value="{{ old('total_runs', 0) }}" min="0" required>
                    @error('total_runs') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="total_wickets" class="form-label fw-bold text-danger">Total Wickets *</label>
                    <input type="number" class="form-control @error('total_wickets') is-invalid @enderror" id="total_wickets" name="total_wickets" value="{{ old('total_wickets', 0) }}" min="0" required>
                    @error('total_wickets') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="biography" class="form-label fw-bold">Biography / Player Scouting Notes</label>
                <textarea class="form-control" id="biography" name="biography" rows="3" placeholder="Enter background details, local match highlights, or tactical records...">{{ old('biography') }}</textarea>
            </div>

            <div class="d-flex justify-content-end border-top pt-3">
                <a href="{{ route('players.index') }}" class="btn btn-light me-2">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Register Player</button>
            </div>
        </form>
    </div>
</div>
@endsection