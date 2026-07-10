<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | CRICKTRACKER-KUET</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #0d1b2a;
            color: #fff;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 4px;
            margin: 4px 10px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #00b4d8;
            color: #fff;
        }
        .admin-main {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-0 shadow">
                <div class="p-3 border-bottom border-secondary">
                    <h5 class="text-white mb-0 fw-bold"><i class="fa-solid fa-gauge me-2 text-info"></i>KUET Admin</h5>
                </div>
                <div class="pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin') ? 'active' : '' }}" href="{{ url('/admin') }}"><i class="fa-solid fa-chart-line me-2"></i>Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/scoring*') ? 'active' : '' }}" href="{{ url('/admin') }}"><i class="fa-solid fa-tower-broadcast me-2"></i>Live Scoring</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/teams*') ? 'active' : '' }}" href="{{ route('teams.index') }}"><i class="fa-solid fa-shield-halved me-2"></i>Manage Teams</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/players*') ? 'active' : '' }}" href="{{ route('players.index') }}"><i class="fa-solid fa-users me-2"></i>Manage Players</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/fixtures*') ? 'active' : '' }}" href="{{ route('fixtures.index') }}"><i class="fa-solid fa-calendar-check me-2"></i>Fixtures</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}"><i class="fa-solid fa-arrow-left me-2"></i>Main Site</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 admin-main py-4">
                @yield('admin-content')
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>