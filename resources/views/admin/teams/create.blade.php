@extends('layouts.admin')

@section('admin-content')
<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Add New Team</h1>
</div>

<div class="card border-0 shadow-sm max-width-md" style="max-width: 600px;">
    <div class="card-body p-4">
        <form action="{{ route('teams.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Team Name (e.g., CSE Crusaders)</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="club_history" class="form-label fw-bold">Club History / Notes</label>
                <textarea class="form-control @error('club_history') is-invalid @enderror" id="club_history" name="club_history" rows="4">{{ old('club_history') }}</textarea>
                @error('club_history')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('teams.index') }}" class="btn btn-light me-2">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Save Team</button>
            </div>
        </form>
    </div>
</div>
@endsection