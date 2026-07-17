@extends('layouts.admin')

@section('admin-content')
<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Edit Team Profile</h1>
</div>

<div class="mb-4">
    <a href="{{ route('teams.index') }}" class="text-decoration-none fw-bold text-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Teams Directory
    </a>
</div>

<div class="card border-0 shadow-sm" style="max-width: 850px;">
    <div class="card-body p-4">
        <form action="{{ route('teams.update', $team->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row g-3">
                <!-- Name -->
                <div class="col-md-8">
                    <label for="name" class="form-label fw-bold">Team Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $team->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Short Name -->
                <div class="col-md-4">
                    <label for="short_name" class="form-label fw-bold">Short Name</label>
                    <input type="text" class="form-control @error('short_name') is-invalid @enderror" id="short_name" name="short_name" value="{{ old('short_name', $team->short_name) }}" placeholder="e.g. KUET">
                    @error('short_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Country -->
                <div class="col-md-6">
                    <label for="country" class="form-label fw-bold">Country</label>
                    <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country', $team->country) }}">
                    @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Head Coach -->
                <div class="col-md-6">
                    <label for="coach" class="form-label fw-bold">Head Coach</label>
                    <input type="text" class="form-control @error('coach') is-invalid @enderror" id="coach" name="coach" value="{{ old('coach', $team->coach) }}">
                    @error('coach')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Team Captain Selection -->
                <div class="col-md-6">
                    <label for="captain_id" class="form-label fw-bold">Team Captain</label>
                    <select class="form-select @error('captain_id') is-invalid @enderror" id="captain_id" name="captain_id">
                        <option value="">-- Select Captain (Optional) --</option>
                        @foreach($players as $player)
                            <option value="{{ $player->id }}" {{ old('captain_id', $team->captain_id) == $player->id ? 'selected' : '' }}>
                                {{ $player->name }} ({{ $player->role ?? 'Player' }})
                            </option>
                        @endforeach
                    </select>
                    @error('captain_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Ranking -->
                <div class="col-md-3">
                    <label for="ranking" class="form-label fw-bold">Global Ranking</label>
                    <input type="number" class="form-control @error('ranking') is-invalid @enderror" id="ranking" name="ranking" value="{{ old('ranking', $team->ranking) }}">
                    @error('ranking')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Founded Year -->
                <div class="col-md-3">
                    <label for="founded_year" class="form-label fw-bold">Founded Year</label>
                    <input type="number" class="form-control @error('founded_year') is-invalid @enderror" id="founded_year" name="founded_year" value="{{ old('founded_year', $team->founded_year) }}">
                    @error('founded_year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Home Ground -->
                <div class="col-md-6">
                    <label for="home_ground" class="form-label fw-bold">Home Ground</label>
                    <input type="text" class="form-control @error('home_ground') is-invalid @enderror" id="home_ground" name="home_ground" value="{{ old('home_ground', $team->home_ground) }}">
                    @error('home_ground')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Website -->
                <div class="col-md-6">
                    <label for="website" class="form-label fw-bold">Official Website</label>
                    <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', $team->website) }}" placeholder="https://example.com">
                    @error('website')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Logo Section -->
                <div class="col-12">
                    <label class="form-label fw-bold">Team Logo</label>
                    <div class="d-flex align-items-center gap-3">
                        @if($team->logo_path)
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $team->logo_path) }}" alt="{{ $team->name }} Logo" class="rounded border p-1" style="width: 70px; height: 70px; object-fit: cover;">
                                <small class="d-block text-muted mt-1">Current Logo</small>
                            </div>
                        @else
                            <div class="bg-light text-muted border rounded d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; font-size: 24px;">
                                <i class="fa-solid fa-image"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo">
                            <small class="text-muted d-block mt-1">Supported Formats: JPG, JPEG, PNG, GIF, SVG (Max: 2MB)</small>
                            @error('logo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="col-12">
                    <label for="description" class="form-label fw-bold">Short Bio / Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2">{{ old('description', $team->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Club History / Notes -->
                <div class="col-12">
                    <label for="club_history" class="form-label fw-bold">Club History / Legacy Notes</label>
                    <textarea class="form-control @error('club_history') is-invalid @enderror" id="club_history" name="club_history" rows="4">{{ old('club_history', $team->club_history) }}</textarea>
                    @error('club_history')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                <a href="{{ route('teams.index') }}" class="btn btn-light me-2">Cancel</a>
                <button type="submit" class="btn btn-success px-4">Update Team</button>
            </div>
        </form>
    </div>
</div>
@endsection