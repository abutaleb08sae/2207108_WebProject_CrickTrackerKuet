@extends('layouts.admin')

@section('admin-content')
<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Publish Sports Bulletin</h1>
</div>

<div class="card border-0 shadow-sm rounded-3 p-4 bg-white" style="max-width: 700px;">
    <form action="{{ route('admin.news.store') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label class="form-label fw-semibold text-secondary">Notice Title</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g., Match postponed due to rain" value="{{ old('title') }}" required>
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold text-secondary">Category</label>
            <select name="category" class="form-select" required>
                <option value="Notice">General Notice</option>
                <option value="Schedule Change">Schedule Change</option>
                <option value="Match Report">Match Report</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold text-secondary">Bulletin Content</label>
            <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="6" placeholder="Write full bulletin details here..." required>{{ old('content') }}</textarea>
            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex gap-2 pt-2">
            <button type="submit" class="btn btn-primary fw-bold px-4">Publish Announcement</button>
            <a href="{{ route('admin.news.index') }}" class="btn btn-light border fw-semibold">Cancel</a>
        </div>
    </form>
</div>
@endsection