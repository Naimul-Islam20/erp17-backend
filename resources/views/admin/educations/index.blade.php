@extends('layouts.admin', ['title' => 'Education'])

@section('content')
<div class="header">
    <h1>Education</h1>
    <a href="{{ route('admin.educations.create') }}" class="btn btn-primary">Create Education</a>
</div>

<div class="list-shell">
    <div class="table-wrap table-wrap-flat">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>YouTube Link</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($educations as $education)
                    <tr>
                        <td>{{ $education->id }}</td>
                        <td>{{ $education->title }}</td>
                        <td>{{ $education->category?->name ?? '—' }}</td>
                        <td>
                            <a href="{{ $education->youtube_link }}" target="_blank" rel="noopener noreferrer">
                                {{ $education->youtube_link }}
                            </a>
                        </td>
                        <td>{{ $education->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="actions-cell">
                            <div class="action-group">
                                <a class="btn btn-primary icon-action-btn" href="{{ route('admin.educations.edit', $education) }}" title="Edit" aria-label="Edit">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.educations.destroy', $education) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger icon-action-btn" type="submit" onclick="return confirm('Delete this education item?')" title="Delete" aria-label="Delete">
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
                        <td colspan="6">No education items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 12px;">{{ $educations->links() }}</div>
</div>
@endsection
