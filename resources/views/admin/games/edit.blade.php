@extends('layouts.admin')

@section('admin-content')
<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Update Match Record & Scorecard</h1>
</div>

<div class="mb-4">
    <a href="{{ route('games.index') }}" class="text-decoration-none fw-bold text-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Match Fixtures
    </a>
</div>

<div class="card border-0 shadow-sm mb-5" style="max-width: 850px;">
    <div class="card-body p-4">
        <form action="{{ route('games.update', $game->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <h5 class="fw-bold text-primary mb-3">
                <i class="fa-solid fa-gear me-2"></i>Fixture Core Info
            </h5>
            <div class="row g-3 mb-4">
                <!-- Match Identifier -->
                <div class="col-md-4">
                    <label for="match_number" class="form-label fw-bold">Match Identifier *</label>
                    <input type="text" class="form-control @error('match_number') is-invalid @enderror" id="match_number" name="match_number" value="{{ old('match_number', $game->match_number) }}" required>
                    @error('match_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Date & Time -->
                <div class="col-md-4">
                    <label for="match_date" class="form-label fw-bold">Match Date & Time *</label>
                    <input type="datetime-local" class="form-control @error('match_date') is-invalid @enderror" id="match_date" name="match_date" value="{{ old('match_date', $game->match_date->format('Y-m-d\TH:i')) }}" required>
                    @error('match_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Ground / Venue -->
                <div class="col-md-4">
                    <label for="venue" class="form-label fw-bold">Match Venue *</label>
                    <input type="text" class="form-control @error('venue') is-invalid @enderror" id="venue" name="venue" value="{{ old('venue', $game->venue) }}" required>
                    @error('venue') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Team 1 -->
                <div class="col-md-6">
                    <label for="team1_id" class="form-label fw-bold">Team 1 (Home / Batting First) *</label>
                    <select class="form-select @error('team1_id') is-invalid @enderror" id="team1_id" name="team1_id" required>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ old('team1_id', $game->team1_id) == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                        @endforeach
                    </select>
                    @error('team1_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Team 2 -->
                <div class="col-md-6">
                    <label for="team2_id" class="form-label fw-bold">Team 2 (Away / Bowling First) *</label>
                    <select class="form-select @error('team2_id') is-invalid @enderror" id="team2_id" name="team2_id" required>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ old('team2_id', $game->team2_id) == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                        @endforeach
                    </select>
                    @error('team2_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <h5 class="fw-bold text-danger mb-3">
                <i class="fa-solid fa-chart-line me-2"></i>Live Match Scoring Engine
            </h5>
            <div class="row g-3 mb-4 bg-light p-3 rounded border">
                <!-- Status Configuration -->
                <div class="col-md-4">
                    <label for="status" class="form-label fw-bold">Match Status *</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Scheduled" {{ old('status', $game->status) == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="Live" {{ old('status', $game->status) == 'Live' ? 'selected' : '' }}>Live</option>
                        <option value="Completed" {{ old('status', $game->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Abandoned" {{ old('status', $game->status) == 'Abandoned' ? 'selected' : '' }}>Abandoned</option>
                    </select>
                </div>

                <!-- Team 1 Score Summary -->
                <div class="col-md-4">
                    <label for="team1_score" class="form-label fw-bold">Team 1 Score Summary</label>
                    <input type="text" class="form-control" id="team1_score" name="team1_score" value="{{ old('team1_score', $game->team1_score) }}" placeholder="e.g., 160/4 (20 ov)">
                </div>

                <!-- Team 2 Score Summary -->
                <div class="col-md-4">
                    <label for="team2_score" class="form-label fw-bold">Team 2 Score Summary</label>
                    <input type="text" class="form-control" id="team2_score" name="team2_score" value="{{ old('team2_score', $game->team2_score) }}" placeholder="e.g., 161/3 (18.4 ov)">
                </div>
            </div>

            <h5 class="fw-bold text-success mb-3">
                <i class="fa-solid fa-trophy me-2"></i>Match Outcomes & Awards
            </h5>
            <div class="row g-3 mb-4">
                <!-- Match Winner Dropdown -->
                <div class="col-md-6">
                    <label for="winner_id" class="form-label fw-bold">Designated Winner</label>
                    <select class="form-select @error('winner_id') is-invalid @enderror" id="winner_id" name="winner_id">
                        <option value="">-- Draw / No Winner Yet --</option>
                        <option value="{{ $game->team1_id }}" {{ old('winner_id', $game->winner_id) == $game->team1_id ? 'selected' : '' }}>{{ $game->team1->name }}</option>
                        <option value="{{ $game->team2_id }}" {{ old('winner_id', $game->winner_id) == $game->team2_id ? 'selected' : '' }}>{{ $game->team2->name }}</option>
                    </select>
                    @error('winner_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Player of the Match Dropdown -->
                <div class="col-md-6">
                    <label for="player_of_the_match_id" class="form-label fw-bold">Player of the Match (MVP)</label>
                    <select class="form-select" id="player_of_the_match_id" name="player_of_the_match_id">
                        <option value="">-- Select Outstanding Player --</option>
                        @foreach($players as $player)
                            <option value="{{ $player->id }}" {{ old('player_of_the_match_id', $game->player_of_the_match_id) == $player->id ? 'selected' : '' }}>
                                {{ $player->name }} ({{ $player->team->name ?? 'No Team' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Verdict Description Text -->
                <div class="col-md-12">
                    <label for="result_description" class="form-label fw-bold">Result Verdict Description</label>
                    <input type="text" class="form-control" id="result_description" name="result_description" value="{{ old('result_description', $game->result_description) }}" placeholder="e.g., ECE won by 7 wickets with 8 balls remaining">
                </div>
            </div>

            <!-- Form Formats Submit -->
            <div class="d-flex justify-content-end border-top pt-3">
                <a href="{{ route('games.index') }}" class="btn btn-light me-2">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection