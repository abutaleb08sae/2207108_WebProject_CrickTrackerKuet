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

    /* Skeleton UI Shimmer Box definitions */
    .skeleton-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        height: 380px;
        position: relative;
        overflow: hidden;
    }
    .skeleton-box::after {
        content: "";
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        transform: translateX(-100%);
        background-image: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 20%, rgba(255,255,255,0.6) 60%, rgba(255,255,255,0) 100%);
        animation: loading-shimmer 1.5s infinite;
    }
    @keyframes loading-shimmer {
        100% { transform: translateX(100%); }
    }
</style>

<div class="cricket-dashboard-wrapper">
    <div class="cricket-container">
        
        <!-- Header Matrix -->
        <div class="dashboard-header">
            <div class="header-left">
                <h1>International Match Center</h1>
                <p class="header-subtitle">Real-time international tournament coverage, live feeds, and fixtures.</p>
            </div>
            <div class="live-badge">
                📰 NEWS API CLIENT ACTIVE
            </div>
        </div>

        <!-- Navigation Toggles -->
        <div class="nav-toggle-container">
            <a href="{{ route('public.international') }}" class="toggle-btn toggle-btn-inactive">
                <span style="font-size: 16px;">🏏</span> Live Scores
            </a>
            <a href="{{ route('public.cricket.news') }}" class="toggle-btn toggle-btn-active">
                <span style="font-size: 16px;">📰</span> Latest News
            </a>
        </div>

        <!-- Async Dynamic Targets Grid Wrapper container -->
        <div id="ajax-news-grid" class="news-grid">
            <!-- Skeleton cards show instantly while data transfers -->
            <div class="skeleton-box"></div>
            <div class="skeleton-box"></div>
            <div class="skeleton-box"></div>
        </div>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Fire structural AJAX execution pipeline target
    fetch("{{ url('/api/international-news-data') }}")
        .then(response => {
            if (!response.ok) throw new Error("External feed system error");
            return response.json();
        })
        .then(articles => {
            renderNewsLayout(articles);
        })
        .catch(error => {
            console.error("AJAX Engine Context Trace Error:", error);
            document.getElementById('ajax-news-grid').innerHTML = `
                <div class="no-data-card">
                    <span style="font-size: 36px;">⚠️</span>
                    <h5 class="fw-bold mt-2">Synchronization Interrupted</h5>
                    <p class="text-muted small mb-0">Failed to establish a asynchronous background connection to the external media API layout stream.</p>
                </div>`;
        });
});

function renderNewsLayout(articles) {
    const targetGrid = document.getElementById('ajax-news-grid');
    
    if (!articles || articles.length === 0) {
        targetGrid.innerHTML = `
            <div class="no-data-card">
                <span style="font-size: 36px;">🏏</span>
                <h5 class="fw-bold mt-2">No Headlines Found</h5>
                <p class="text-muted small mb-0">No international cricket news reports matched your lookup configuration criteria right now.</p>
            </div>`;
        return;
    }

    let compiledHtml = '';
    
    articles.forEach(article => {
        // Enforce structural data sanitation cleanup constraints
        if (!article.title || article.title === '[Removed]') return;

        let thumbnailImg = article.urlToImage 
            ? `<img src="${article.urlToImage}" class="news-img" alt="News Feed thumbnail">`
            : `<div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light"><span style="font-size: 28px;">🏏</span></div>`;

        let snippetText = article.description ? article.description : 'Click details indicator below to process article entry content stream.';
        if (snippetText.length > 130) snippetText = snippetText.substring(0, 130) + '...';

        let explicitTitle = article.title.length > 70 ? article.title.substring(0, 70) + '...' : article.title;
        
        // Calculate dynamic timing formats purely via JavaScript fallback constraints
        let timeLabel = 'Recently Updated';
        if(article.publishedAt) {
            let publishedTime = new Date(article.publishedAt);
            timeLabel = publishedTime.toLocaleDateString(undefined, { month: 'short', day: 'numeric', hour: '2-digit', minute:'2-digit' });
        }

        compiledHtml += `
            <div class="news-card">
                <div class="image-wrapper">
                    ${thumbnailImg}
                    <span class="source-tag">${article.source.name || 'Cricket Update'}</span>
                </div>
                <div class="news-body">
                    <div class="news-time">📅 ${timeLabel}</div>
                    <h3 class="news-title">${explicitTitle}</h3>
                    <p class="news-desc">${snippetText}</p>
                </div>
                <div class="news-footer">
                    <a href="${article.url}" target="_blank" class="read-btn">Read Full Article ↗</a>
                </div>
            </div>`;
    });

    targetGrid.innerHTML = compiledHtml;
}
</script>
@endsection