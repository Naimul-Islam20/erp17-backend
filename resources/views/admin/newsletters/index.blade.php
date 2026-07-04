@extends('layouts.admin', ['title' => 'Newsletter'])

@section('content')
<div class="header">
    <h1>Newsletter</h1>
    <a href="{{ route('admin.newsletters.create') }}" class="btn btn-primary">Create Newsletter</a>
</div>

<div class="list-shell">
    <div class="table-wrap table-wrap-flat">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($newsletters as $newsletter)
                    <tr>
                        <td>{{ $newsletter->id }}</td>
                        <td style="max-width: 260px; white-space: normal;">
                            {{ \Illuminate\Support\Str::words($newsletter->title, 3, '...') }}
                        </td>
                        <td>{{ $newsletter->category?->name ?? '-' }}</td>
                        <td>{{ $newsletter->published_at?->format('Y-m-d') ?? $newsletter->created_at?->format('Y-m-d') }}</td>
                        <td>
                            <img
                                src="{{ asset($newsletter->image_path) }}"
                                alt="{{ \Illuminate\Support\Str::words($newsletter->title, 2, '...') }}"
                                style="width:72px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;">
                        </td>
                        <td class="actions-cell">
                            <div class="action-group">
                                <a class="btn btn-muted icon-action-btn" href="{{ route('admin.newsletters.show', $newsletter) }}" title="View" aria-label="View">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a>
                                <a class="btn btn-primary icon-action-btn" href="{{ route('admin.newsletters.edit', $newsletter) }}" title="Edit" aria-label="Edit">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.newsletters.destroy', $newsletter) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger icon-action-btn" type="submit" onclick="return confirm('Delete this newsletter?')" title="Delete" aria-label="Delete">
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
                        <td colspan="6">No newsletter items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 12px;">{{ $newsletters->links() }}</div>
</div>
@endsection
