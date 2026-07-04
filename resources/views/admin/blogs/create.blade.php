@extends('layouts.admin', ['title' => 'Create Blog'])

@section('content')
<div class="header">
    <h1>Create Blog</h1>
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card">
    @include('admin.blogs._form', [
        'action' => route('admin.blogs.store'),
        'submitLabel' => 'Save Blog',
        'submitConfirm' => 'Create this blog?',
        'isEdit' => false,
    ])
</div>
@endsection
