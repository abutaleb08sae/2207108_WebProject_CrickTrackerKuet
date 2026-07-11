@extends('layouts.public')

@section('title', 'Tournament Standings - CRICKTRACKER-KUET')

@section('header_banner')
    <header class="hero-banner">
        <div class="container text-center">
            <h1 class="fw-800 mb-2">Official League Standings</h1>
            <p class="text-white-50 fs-6 mb-0">Dynamically calculated win/loss records and tournament point tables.</p>
        </div>
    </header>
@endsection

@section('content')
    <h2 class="section-title mb-4">Points Leaderboard Matrix</h2>
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-dark text-uppercase fs-7 tracking-wider">
                    <tr>
                        <th class="text-start ps-4 py-3">Department Team</th>
                        <th>Played</th>
                        <th>Won</th>
                        <th>Lost</th>
                        <th>Tied</th>
                        <th class="text-info">Points</th>
                    </tr>
                </thead>
                <tbody class="fs-6">
                    @foreach($standings as $teamId => $stats)
                        <tr>
                            <td class="text-start ps-4 fw-bold text-dark">
                                {{ $stats['name'] }} <span class="badge bg-light text-secondary border ms-2 small">{{ $stats['slug'] }}</span>
                            </td>
                            <td class="fw-medium text-secondary">{{ $stats['played'] }}</td>
                            <td class="text-success fw-bold">{{ $stats['won'] }}</td>
                            <td class="text-danger fw-medium">{{ $stats['lost'] }}</td>
                            <td class="text-muted fw-medium">{{ $stats['tied'] }}</td>
                            <td class="fw-bold text-info bg-light-subtle">{{ $stats['points'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection