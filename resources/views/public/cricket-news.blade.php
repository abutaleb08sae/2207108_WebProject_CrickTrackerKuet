@extends('layouts.public')

@section('content')
<style>
    .cricket-dashboard-wrapper {
        background-color: #f1f5f9;
        padding: 48px 20px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #0f172a;
        text-align: left;
    }
    .cricket-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    .dashboard-header {
        background: #ffffff;
        border-top: 4px solid #0284c7;
        border-radius: 12px;
        padding: 28px 32px;
        margin-bottom: 24px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    .header-left h1 {
        font-size: 32px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.75px;
    }
    .header-subtitle {
        color: #64748b;
        font-size: 15px;
        margin: 6px 0 0 0;
        font-weight: 500;
    }
    .live-badge {
        background-color: #fffbeb;
        color: #d97706;
        border: 1px solid #fef3c7;
        padding: 10px 20px;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 12px;
        letter-spacing: 0.75px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    /* Segment Control / Toggle Buttons Style */
    .nav-toggle-container {
        display: flex;
        gap: 12px;
        margin-bottom: 40px;
    }
    .toggle-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none !important;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    .toggle-btn-active {
        background-color: #0284c7;
        color: #ffffff !important;
        box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.2);
    }
    .toggle-btn-inactive {
        background-color: #ffffff;
        color: #475569 !important;
        border-color: #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .toggle-btn-inactive:hover {
        background-color: #f8fafc;
        border-color: #cbd5e1;
    }

    /* News Cards Grid layout */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 28px;
    }
    .news-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .news-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }
    .image-wrapper {
        position: relative;
        background-color: #e2e8f0;
        height: 200px;
        width: 100%;
    }
    .news-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .source-tag {
        position: absolute;
        bottom: 12px;
        left: 12px;
        background: rgba(15, 23, 42, 0.8);
        color: #ffffff;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }
    .news-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        gap: 12px;
    }
    .news-time {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
    }
    .news-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.4;
        margin: 0;
    }
    .news-desc {
        font-size: 14px;
        color: #475569;
        line-height: 1.5;
        margin: 0;
    }
    .news-footer {
        padding: 16px 24px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        margin-top: auto;
    }
    .read-btn {
        display: block;
        text-align: center;
        background: #ffffff;
        color: #0284c7;
        border: 1px solid #e2e8f0;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }
    .read-btn:hover {
        background: #0284c7;
        color: #ffffff;
        border-color: #0284c7;
    }
    .no-data-card {
        background: #ffffff;
        padding: 40px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        text-align: center;
        grid-column: 1 / -1;
    }
</style>

<div class="cricket-dashboard-wrapper">
    <div class="cricket-container">
        
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="header-left">
                <h1>International Match Center</h1>
                <p class="header-subtitle">Real-time international tournament coverage, live feeds, and fixtures.</p>
            </div>
            <div class="live-badge">
                📰 NEWS LIVE ENGINE
            </div>
        </div>

        <!-- Interactive Navigation Module Options Toggle -->
        <div class="nav-toggle-container">
            <a href="{{ route('public.international') }}" class="toggle-btn toggle-btn-inactive">
                <span style="font-size: 16px;">🏏</span> Live Scores
            </a>
            <a href="{{ route('public.cricket.news') }}" class="toggle-btn toggle-btn-active">
                <span style="font-size: 16px;">📰</span> Latest News
            </a>
        </div>

        <!-- News Content Grid -->
        <div class="news-grid">
            @forelse($articles as $article)
                @if(empty($article['title']) || $article['title'] == '[Removed]')
                    @continue
                @endif

                <div class="news-card">
                    <div class="image-wrapper">
                        @if(!empty($article['urlToImage']))
                            <img src="{{ $article['urlToImage'] }}" class="news-img" alt="News Image">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                <span style="font-size: 28px;">🏏</span>
                            </div>
                        @endif
                        <span class="source-tag">{{ $article['source']['name'] ?? 'Cricket Feed' }}</span>
                    </div>

                    <div class="news-body">
                        <div class="news-time">
                            📅 {{ \Carbon\Carbon::parse($article['publishedAt'])->diffForHumans() }}
                        </div>
                        <h3 class="news-title">{{ Str::limit($article['title'], 70) }}</h3>
                        <p class="news-desc">{{ Str::limit($article['description'] ?? 'Click below to view full summary detail logs.', 130) }}</p>
                    </div>

                    <div class="news-footer">
                        <a href="{{ $article['url'] }}" target="_blank" class="read-btn">
                            Read Full Article ↗
                        </a>
                    </div>
                </div>
            @empty
                <div class="no-data-card">
                    <span style="font-size: 36px;">⚠️</span>
                    <h5 class="fw-bold mt-2">No News Feeds Available</h5>
                    <p class="text-muted small mb-0">We are currently unable to retrieve external global news aggregates. Please try refreshing again later.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection