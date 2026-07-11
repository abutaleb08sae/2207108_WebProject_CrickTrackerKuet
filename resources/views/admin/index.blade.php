@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Dashboard Overview</h1>
    <span class="badge bg-dark p-2">Session: Active</span>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-primary-subtle text-primary rounded-3 me-3">
                    <i class="fa-solid fa-shield-halved fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Teams</h6>
                    <h3 class="fw-bold mb-0">{{ $teamsCount }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-success-subtle text-success rounded-3 me-3">
                    <i class="fa-solid fa-users fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Registered Players</h6>
                    <h3 class="fw-bold mb-0">{{ $playersCount }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-danger-subtle text-danger rounded-3 me-3">
                    <i class="fa-solid fa-tower-broadcast fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Live Matches</h6>
                    <h3 class="fw-bold mb-0">{{ $liveMatchesCount }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white p-4 rounded-3 shadow-sm">
    <h5 class="fw-bold mb-3">System Notice</h5>
    <p class="text-muted mb-0">Welcome to the CRICKTRACKER-KUET Management Panel. Use the sidebar workspace layout to maintain campus match scoring records data tables and communicate updates via the news panel.</p>
</div>
@endsection