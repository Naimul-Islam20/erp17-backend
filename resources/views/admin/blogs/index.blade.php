@extends('layouts.admin', ['title' => 'Blog'])

@section('content')
<div class="header">
    <h1>Blog</h1>
    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">Create Blog</a>
</div>

<div class="card">
    <div class="table-wrap">
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
                            {{ \Illuminate\Support\Str::limit($blog->title, 55) }}
                        </td>
                        <td>{{ $blog->blocks_count }}</td>
                        <td>{{ $blog->updated_at?->format('Y-m-d H:i') }}</td>
                        <td class="actions-cell">
                            <div class="action-group">
                                <a class="btn btn-muted" href="{{ route('admin.blogs.show', $blog) }}">View</a>
                                <a class="btn btn-primary" href="{{ route('admin.blogs.edit', $blog) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this blog?')">Delete</button>
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
