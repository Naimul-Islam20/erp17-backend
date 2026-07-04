@extends('layouts.admin', ['title' => 'Contact'])

@section('content')
<div class="header">
    <h1>Contact</h1>
</div>

<div class="list-shell">
    <div class="table-wrap table-wrap-flat">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Company Name</th>
                <th>Mobile</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($expertSessions as $session)
            <tr>
                <td>{{ $session->name }}</td>
                <td>{{ $session->company_name }}</td>
                <td>{{ $session->mobile }}</td>
                <td>
                    <span class="status {{ $session->status === 'new' ? 'status-unread' : 'status-review' }}">
                        {{ $session->status }}
                    </span>
                </td>
                <td class="actions-cell">
                    <div class="action-group">
                        <a class="btn btn-muted icon-action-btn" href="{{ route('admin.expert-sessions.show', $session) }}" title="View" aria-label="View">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('admin.expert-sessions.destroy', $session) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger icon-action-btn" type="submit" onclick="return confirm('Delete this request?')" title="Delete" aria-label="Delete">
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
                <td colspan="5">No contact records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div style="margin-top: 12px;">{{ $expertSessions->links() }}</div>
</div>
@endsection
