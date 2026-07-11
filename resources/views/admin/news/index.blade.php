@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-dark">Manage News & Bulletins</h1>
    <a href="{{ route('admin.news.create') }}" class="btn btn-primary fw-bold">
        <i class="fa-solid fa-plus me-1"></i> Post New Notice
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
@endif

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="ps-4">Title</th>
                    <th>Category</th>
                    <th>Posted Date</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $item)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">{{ $item->title }}</td>
                        <td><span class="badge bg-secondary px-2 py-1">{{ $item->category }}</span></td>
                        <td class="text-muted small">{{ $item->created_at->format('M d, Y h:i A') }}</td>
                        <td class="text-end pe-4">
                            <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this announcement permanently?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-newspaper fs-2 d-block mb-2 text-secondary"></i>
                            No news updates published yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection