@extends('layouts.admin')

@section('admin-content')
<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Schedule New Match</h1>
</div>

<div class="card border-0 shadow-sm" style="max-width: 700px;">
    <div class="card-body p-4">
        <form action="{{ route('fixtures.store') }}" method="POST">
            @csrf
            
            <!-- Teams Row -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="team_one_id" class="form-label fw-bold">Team 1 (Home/Batting First)</label>
                    <select class="form-select @error('team_one_id') is-invalid @enderror" id="team_one_id" name="team_one_id">
                        <option value="">-- Select Team 1 --</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                    @error('team_one_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="team_two_id" class="form-label fw-bold">Team 2 (Away/Bowling First)</label>
                    <select class="form-select @error('team_two_id') is-invalid @enderror" id="team_two_id" name="team_two_id">
                        <option value="">-- Select Team 2 --</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                    @error('team_two_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- New Row: Tournament Selection Logic -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="tournament_select" class="form-label fw-bold">Tournament Category</label>
                    <select class="form-select @error('tournament_type') is-invalid @enderror" id="tournament_select" onchange="toggleTournamentInput(this.value)">
                        <option value="Inter Department Cricket Tournament">Inter Department Cricket Tournament</option>
                        <option value="Futsal Tournament">Futsal Tournament</option>
                        <option value="Others">Others (Write custom name)</option>
                    </select>
                </div>
                <div class="col-md-6" id="custom_tournament_wrapper" style="display: none;">
                    <label for="tournament_type" class="form-label fw-bold">Custom Tournament Name</label>
                    <input type="text" class="form-control @error('tournament_type') is-invalid @enderror" id="tournament_type" name="tournament_type" value="Inter Department Cricket Tournament" placeholder="Enter tournament name">
                    @error('tournament_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Date & Dynamic Venue Row -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="match_datetime" class="form-label fw-bold">Match Date & Start Time</label>
                    <input type="datetime-local" class="form-control @error('match_datetime') is-invalid @enderror" id="match_datetime" name="match_datetime">
                    @error('match_datetime') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="venue_select" class="form-label fw-bold">Match Venue Location</label>
                    <select class="form-select mb-2" id="venue_select" onchange="toggleVenueInput(this.value)">
                        <option value="KUET Main Playground">KUET Main Playground</option>
                        <option value="KUET Gymnasium Ground">KUET Gymnasium Ground</option>
                        <option value="KUET SWC Premises">KUET SWC Premises</option>
                        <option value="Others">Others (Specify alternative)</option>
                    </select>
                    <!-- Hidden real structural input tracking field -->
                    <input type="text" class="form-control @error('venue') is-invalid @enderror" id="venue" name="venue" value="KUET Main Playground" style="display: none;" placeholder="Enter custom location name">
                    @error('venue') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Match Status Input Box -->
            <div class="mb-4" style="max-width: 330px;">
                <label for="status" class="form-label fw-bold">Match Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="Upcoming">Upcoming</option>
                    <option value="Live">Live</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <!-- Action Form Operations Controls Section -->
            <div class="d-flex justify-content-end">
                <a href="{{ route('fixtures.index') }}" class="btn btn-light me-2">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Schedule Match</button>
            </div>
        </form>
    </div>
</div>

<script>
/**
 * Toggles a secondary user-input panel if custom tournament selections occur
 */
function toggleTournamentInput(value) {
    const inputWrapper = document.getElementById('custom_tournament_wrapper');
    const inputField = document.getElementById('tournament_type');
    
    if (value === 'Others') {
        inputWrapper.style.display = 'block';
        inputField.value = '';
        inputField.focus();
    } else {
        inputWrapper.style.display = 'none';
        inputField.value = value;
    }
}

/**
 * Handles toggling dropdown options versus writing down unique custom playground options
 */
function toggleVenueInput(value) {
    const actualInputField = document.getElementById('venue');
    
    if (value === 'Others') {
        actualInputField.style.display = 'block';
        actualInputField.value = '';
        actualInputField.focus();
    } else {
        actualInputField.style.display = 'none';
        actualInputField.value = value;
    }
}
</script>
@endsection