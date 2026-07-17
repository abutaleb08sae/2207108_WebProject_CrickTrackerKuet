@extends('layouts.admin')

@section('admin-content')
<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Edit Player Profile</h1>
</div>

<div class="card border-0 shadow-sm" style="max-width: 700px;">
    <div class="card-body p-4">
        <form action="{{ route('players.update', $player->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-3 mb-3">
                <!-- Full Name -->
                <div class="col-md-6">
                    <label for="name" class="form-label fw-bold">Full Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $player->name) }}">
                    @error('name') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
                
                <!-- Student ID -->
                <div class="col-md-6">
                    <label for="student_id" class="form-label fw-bold">Student ID</label>
                    <input type="text" class="form-control @error('student_id') is-invalid @enderror" id="student_id" name="student_id" value="{{ old('student_id', $player->student_id) }}">
                    @error('student_id') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <!-- Assigned Squad / Team -->
                <div class="col-md-6">
                    <label for="team_id" class="form-label fw-bold">Assigned Squad / Team</label>
                    <select class="form-select @error('team_id') is-invalid @enderror" id="team_id" name="team_id">
                        <option value="">-- Choose Team --</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ old('team_id', $player->team_id) == $team->id ? 'selected' : '' }}>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('team_id') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
                
                <!-- Player Role -->
                <div class="col-md-6">
                    <label for="role" class="form-label fw-bold">Player Role</label>
                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                        <option value="">-- Choose Specialization --</option>
                        <option value="Batsman" {{ old('role', $player->role) == 'Batsman' ? 'selected' : '' }}>Batsman</option>
                        <option value="Bowler" {{ old('role', $player->role) == 'Bowler' ? 'selected' : '' }}>Bowler</option>
                        <option value="All-rounder" {{ old('role', $player->role) == 'All-rounder' ? 'selected' : '' }}>All-rounder</option>
                        <option value="Wicketkeeper" {{ old('role', $player->role) == 'Wicketkeeper' ? 'selected' : '' }}>Wicketkeeper</option>
                    </select>
                    @error('role') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
            </div>

            <div class="row g-3 mb-4">
                <!-- Matches Played -->
                <div class="col-md-4">
                    <label for="matches_played" class="form-label fw-bold">Matches Played</label>
                    <input type="number" class="form-control @error('matches_played') is-invalid @enderror" id="matches_played" name="matches_played" value="{{ old('matches_played', $player->matches_played) }}" min="0">
                    @error('matches_played') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
                
                <!-- Total Runs -->
                <div class="col-md-4">
                    <label for="total_runs" class="form-label fw-bold">Total Runs</label>
                    <input type="number" class="form-control @error('total_runs') is-invalid @enderror" id="total_runs" name="total_runs" value="{{ old('total_runs', $player->total_runs) }}" min="0">
                    @error('total_runs') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
                
                <!-- Total Wickets -->
                <div class="col-md-4">
                    <label for="total_wickets" class="form-label fw-bold">Total Wickets</label>
                    <input type="number" class="form-control @error('total_wickets') is-invalid @enderror" id="total_wickets" name="total_wickets" value="{{ old('total_wickets', $player->total_wickets) }}" min="0">
                    @error('total_wickets') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-end border-top pt-3">
                <a href="{{ route('players.index') }}" class="btn btn-light me-2">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Update Player</button>
            </div>
        </form>
    </div>
</div>
@endsection