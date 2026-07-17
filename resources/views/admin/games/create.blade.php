@extends('layouts.admin')

@section('admin-content')
<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Schedule New Match</h1>
</div>

<div class="mb-4">
    <a href="{{ route('games.index') }}" class="text-decoration-none fw-bold text-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Match Fixtures
    </a>
</div>

<div class="card border-0 shadow-sm" style="max-width: 750px;">
    <div class="card-body p-4">
        <form action="{{ route('games.store') }}" method="POST">
            @csrf
            
            <div class="row g-3 mb-3">
                <!-- Match Identifier -->
                <div class="col-md-6">
                    <label for="match_number" class="form-label fw-bold">Match Identifier / Number *</label>
                    <input type="text" class="form-control @error('match_number') is-invalid @enderror" id="match_number" name="match_number" value="{{ old('match_number') }}" placeholder="e.g., Match-01, Quarter-Final 1" required>
                    @error('match_number') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>

                <!-- Match Status -->
                <div class="col-md-6">
                    <label for="status" class="form-label fw-bold">Initial Status *</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="Scheduled" {{ old('status') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="Live" {{ old('status') == 'Live' ? 'selected' : '' }}>Live</option>
                        <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Abandoned" {{ old('status') == 'Abandoned' ? 'selected' : '' }}>Abandoned</option>
                    </select>
                    @error('status') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <!-- Team 1 -->
                <div class="col-md-6">
                    <label for="team1_id" class="form-label fw-bold">Team 1 (Home / Batting First) *</label>
                    <select class="form-select @error('team1_id') is-invalid @enderror" id="team1_id" name="team1_id" required>
                        <option value="">-- Choose Team 1 --</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ old('team1_id') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                        @endforeach
                    </select>
                    @error('team1_id') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>

                <!-- Team 2 -->
                <div class="col-md-6">
                    <label for="team2_id" class="form-label fw-bold">Team 2 (Away / Bowling First) *</label>
                    <select class="form-select @error('team2_id') is-invalid @enderror" id="team2_id" name="team2_id" required>
                        <option value="">-- Choose Team 2 --</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ old('team2_id') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                        @endforeach
                    </select>
                    @error('team2_id') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
            </div>

            <div class="row g-3 mb-4">
                <!-- Match Date and Time -->
                <div class="col-md-6">
                    <label for="match_date" class="form-label fw-bold">Match Date & Time *</label>
                    <input type="datetime-local" class="form-control @error('match_date') is-invalid @enderror" id="match_date" name="match_date" value="{{ old('match_date') }}" required>
                    @error('match_date') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>

                <!-- Ground / Venue -->
                <div class="col-md-6">
                    <label for="venue" class="form-label fw-bold">Match Venue *</label>
                    <input type="text" class="form-control @error('venue') is-invalid @enderror" id="venue" name="venue" value="{{ old('venue', 'KUET Central Playground') }}" required>
                    @error('venue') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
            </div>

            <!-- Form Submissions -->
            <div class="d-flex justify-content-end border-top pt-3">
                <a href="{{ route('games.index') }}" class="btn btn-light me-2">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Create Fixture</button>
            </div>
        </form>
    </div>
</div>
@endsection