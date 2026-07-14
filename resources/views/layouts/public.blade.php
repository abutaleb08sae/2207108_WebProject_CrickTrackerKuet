<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CRICKTRACKER-KUET')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #2D3748;
        }
        .navbar {
            background-color: #0F172A !important;
            border-bottom: 3px solid #0EA5E9;
        }
        .hero-banner {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            color: #FFFFFF;
            padding: 40px 0;
            border-bottom: 1px solid #334155;
        }
        .section-title {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #64748B;
            font-weight: 700;
            position: relative;
            padding-left: 15px;
        }
        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 2px;
            bottom: 2px;
            width: 4px;
            background-color: #0EA5E9;
            border-radius: 2px;
        }
        .cric-card {
            background: #FFFFFF;
            border-radius: 12px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            transition: all 0.25s ease;
        }
        .cric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            border-color: #CBD5E1;
        }
        .nav-link-custom {
            color: #94A3B8;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s ease;
            text-decoration: none;
        }
        .nav-link-custom:hover, .nav-link-custom.active {
            color: #0EA5E9;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="{{ route('public.home') }}">
                <i class="fa-solid fa-trophy text-info me-2 fs-5"></i>CRICKTRACKER-KUET
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <div class="navbar-nav mx-auto gap-4 my-2 my-lg-0">
                    <a href="{{ route('public.home') }}" class="nav-link-custom {{ Request::routeIs('public.home') ? 'active' : '' }}"><i class="fa-solid fa-satellite-dish me-1"></i> Live Center</a>
                    <a href="{{ route('public.standings') }}" class="nav-link-custom {{ Request::routeIs('public.standings') ? 'active' : '' }}"><i class="fa-solid fa-list-ol me-1"></i> Points Table</a>
                    <a href="{{ route('public.fixtures') }}" class="nav-link-custom {{ Request::routeIs('public.fixtures') ? 'active' : '' }}"><i class="fa-solid fa-calendar-days me-1"></i> Schedules</a>
                    <a href="{{ route('public.results') }}" class="nav-link-custom {{ Request::routeIs('public.results') ? 'active' : '' }}"><i class="fa-solid fa-square-poll-horizontal me-1"></i> Recent Results</a>
                    <a href="{{ route('public.news.index') }}" class="nav-link-custom {{ Request::routeIs('public.news.*') ? 'active' : '' }}"><i class="fa-solid fa-newspaper me-1"></i> News Board</a>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if(Session::has('user_id'))
                        <span class="badge bg-secondary p-2 me-1 text-white">
                            <i class="fa-solid fa-user me-1"></i> {{ Session::get('user_name') }}
                        </span>
                        @if(Session::get('user_role') === 'admin')
                            <a href="{{ route('admin.index') }}" class="btn btn-warning btn-sm fw-bold px-3 py-2 text-dark rounded-3 shadow-sm">
                                <i class="fa-solid fa-user-gear me-1"></i> Admin Panel
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="d-inline mb-0">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm fw-bold px-3 py-2 rounded-3 shadow-sm">
                                <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Sign Out
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-info btn-sm fw-bold px-3 py-2 text-dark rounded-3 shadow-sm">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Sign In
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    @yield('header_banner')

    <main class="container my-5">
        @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ Session::get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white text-center py-4 mt-5 border-top border-light">
        <p class="mb-0 small text-muted">© 2026 CRICKTRACKER-KUET. Built for smooth athletic data propagation.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>