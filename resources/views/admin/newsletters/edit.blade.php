@extends('layouts.admin', ['title' => 'Edit Newsletter'])

@section('content')
<div class="header">
    <h1>Edit Newsletter</h1>
    <a href="{{ route('admin.newsletters.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card">
    @include('admin.newsletters._form', [
        'action' => route('admin.newsletters.update', $newsletter),
        'submitLabel' => 'Update Newsletter',
        'submitConfirm' => 'Update this newsletter?',
        'isEdit' => true,
        'newsletter' => $newsletter,
    ])
</div>
@endsection
