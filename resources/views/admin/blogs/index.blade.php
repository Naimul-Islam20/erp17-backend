@extends('layouts.admin', ['title' => 'Blog'])

@section('content')
<div class="header">
    <h1>Blog</h1>
    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">Create Blog</a>
</div>

<div class="list-shell">
    <div class="table-wrap table-wrap-flat">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Blog Title</th>
                    <th>Blocks</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($blogs as $blog)
                    <tr>
                        <td>{{ $blog->id }}</td>
                        <td>{{ $blog->category?->name ?? '-' }}</td>
                        <td style="max-width: 280px; white-space: normal;">
                            {{ \Illuminate\Support\Str::words($blog->title, 3, '...') }}
                        </td>
                        <td>{{ $blog->blocks_count }}</td>
                        <td>{{ $blog->updated_at?->format('Y-m-d H:i') }}</td>
                        <td class="actions-cell">
                            <div class="action-group">
                                <a class="btn btn-muted icon-action-btn" href="{{ route('admin.blogs.show', $blog) }}" title="View" aria-label="View">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a>
                                <a class="btn btn-primary icon-action-btn" href="{{ route('admin.blogs.edit', $blog) }}" title="Edit" aria-label="Edit">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger icon-action-btn" type="submit" onclick="return confirm('Delete this blog?')" title="Delete" aria-label="Delete">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M3 6h18"></path>
                                            <path d="M8 6V4h8v2"></path>
                                            <path d="M19 6l-1 14H6L5 6"></path>
                                            <path d="M10 11v6"></path>
                                            <path d="M14 11v6"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No blogs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 12px;">{{ $blogs->links() }}</div>
</div>
@endsection
