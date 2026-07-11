@extends('layouts.admin')

@section('admin-content')
<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Live Match Scoring Panel</h1>
</div>

<div class="row">
    @forelse($liveFixtures as $fixture)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm border-start border-danger border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-danger"><i class="fa-solid fa-circle text-white me-1 small animate-pulse"></i> LIVE</span>
                        <small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i>{{ $fixture->venue }}</small>
                    </div>
                    <h5 class="card-title fw-bold text-dark mt-2">
                        {{ $fixture->teamOne->name }} <span class="text-muted fw-normal fs-6">vs</span> {{ $fixture->teamTwo->name }}
                    </h5>
                    <p class="card-text text-muted small"><i class="fa-solid fa-clock me-1"></i> Started: {{ date('h:i A', strtotime($fixture->match_datetime)) }}</p>
                    <a href="{{ route('scoring.dashboard', $fixture->id) }}" class="btn btn-outline-danger btn-sm w-100 mt-2 fw-bold">
                        <i class="fa-solid fa-tower-broadcast me-1"></i> Open Control Room
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm text-center py-5 text-muted">
                <div class="card-body">
                    <i class="fa-solid fa-tower-broadcast fa-3x mb-3 text-secondary"></i>
                    <h5 class="fw-bold">No Active Matches Found</h5>
                    <p class="mb-0">Head to the <strong>Fixtures</strong> module, edit an upcoming match, and change its status configuration to <strong>"Live"</strong> to manage it here.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection