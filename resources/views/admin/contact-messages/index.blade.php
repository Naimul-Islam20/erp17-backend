@extends('layouts.admin', ['title' => 'Contact Form'])

@section('content')
<div class="header">
    <div>
        <h1>Contact Form</h1>
        <p style="margin:6px 0 0;color:#64748b;font-size:14px;">Submissions from the public contact page.</p>
    </div>
</div>

<div class="list-shell">
    <div class="table-wrap table-wrap-flat">
    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($messages as $message)
            <tr>
                <td>{{ $message->full_name }}</td>
                <td>{{ $message->email }}</td>
                <td>{{ $message->phone ?? '—' }}</td>
                <td>
                    <span class="status {{ $message->status === 'unread' ? 'status-unread' : 'status-read' }}">
                        {{ $message->status }}
                    </span>
                </td>
                <td>{{ $message->created_at?->format('Y-m-d H:i') }}</td>
                <td class="actions-cell">
                    <div class="action-group">
                        <a class="btn btn-muted icon-action-btn" href="{{ route('admin.contact-messages.show', $message) }}" title="View" aria-label="View">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('admin.contact-messages.destroy', $message) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger icon-action-btn" type="submit" onclick="return confirm('Delete this message?')" title="Delete" aria-label="Delete">
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
                <td colspan="6">No messages found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div style="margin-top: 12px;">{{ $messages->links() }}</div>
</div>
@endsection