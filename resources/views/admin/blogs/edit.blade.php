@extends('layouts.admin', ['title' => 'Edit Blog'])

@section('content')
<div class="header">
    <h1>Edit Blog</h1>
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card">
    @include('admin.blogs._form', [
        'action' => route('admin.blogs.update', $blog),
        'submitLabel' => 'Update Blog',
        'submitConfirm' => 'Update this blog?',
        'isEdit' => true,
        'blog' => $blog,
    ])
</div>
@endsection
