@extends('layouts.admin', ['title' => 'Edit Education'])

@section('content')
<div class="header">
    <h1>Edit Education</h1>
    <a href="{{ route('admin.educations.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.educations.update', $education) }}">
        @csrf
        @method('PUT')
        @include('admin.educations._form')

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit" onclick="return confirm('Update this education item?')">Update Education</button>
        </div>
    </form>
</div>
@endsection
