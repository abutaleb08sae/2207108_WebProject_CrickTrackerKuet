@extends('layouts.admin')

@section('admin-content')
<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Modify Fixture Schedule</h1>
</div>

<div class="card border-0 shadow-sm" style="max-width: 700px;">
    <div class="card-body p-4">
        <form action="{{ route('fixtures.update', $fixture->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Team 1</label>
                    <select class="form-select" name="team_one_id">
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ $fixture->team_one_id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Team 2</label>
                    <select class="form-select" name="team_two_id">
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ $fixture->team_two_id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="match_datetime" class="form-label fw-bold">Match Date & Time</label>
                    <input type="datetime-local" class="form-control" id="match_datetime" name="match_datetime" value="{{ date('Y-m-d\TH:i', strtotime($fixture->match_datetime)) }}">
                </div>
                <div class="col-md-6">
                    <label for="venue" class="form-label fw-bold">Venue Location</label>
                    <input type="text" class="form-control" id="venue" name="venue" value="{{ $fixture->venue }}">
                </div>
            </div>

            <div class="mb-4" style="max-width: 330px;">
                <label for="status" class="form-label fw-bold">Match Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="Upcoming" {{ $fixture->status == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="Live" {{ $fixture->status == 'Live' ? 'selected' : '' }}>Live</option>
                    <option value="Completed" {{ $fixture->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('fixtures.index') }}" class="btn btn-light me-2">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Update Fixture</button>
            </div>
        </form>
    </div>
</div>
@endsection