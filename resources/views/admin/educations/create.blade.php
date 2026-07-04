@extends('layouts.admin', ['title' => 'Create Education'])

@section('content')
<div class="header">
    <h1>Create Education</h1>
    <a href="{{ route('admin.educations.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.educations.store') }}">
        @csrf
        @include('admin.educations._form')

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit" onclick="return confirm('Create this education item?')">Save Education</button>
        </div>
    </form>
</div>
@endsection
