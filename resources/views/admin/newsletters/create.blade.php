@extends('layouts.admin', ['title' => 'Create Newsletter'])

@section('content')
<div class="header">
    <h1>Create Newsletter</h1>
    <a href="{{ route('admin.newsletters.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card">
    @include('admin.newsletters._form', [
        'action' => route('admin.newsletters.store'),
        'submitLabel' => 'Save Newsletter',
        'submitConfirm' => 'Create this newsletter?',
        'isEdit' => false,
    ])
</div>
@endsection
