@extends('layouts.admin', ['title' => 'Dashboard'])

@section('content')
<div class="header">
    <h1>Dashboard</h1>
</div>

<div class="stats-grid">
    <div class="card stat-card">
        <p class="stat-label">Total Users</p>
        <p class="stat-value">{{ $totalUsers }}</p>
        <div class="stat-meta">Users</div>
    </div>
    <div class="card stat-card">
        <p class="stat-label">Quote Requests</p>
        <p class="stat-value">{{ $newQuoteRequests }}</p>
        <div class="stat-meta">New</div>
    </div>
    <div class="card stat-card">
        <p class="stat-label">Contact</p>
        <p class="stat-value">{{ $newExpertSessions }}</p>
        <div class="stat-meta">New</div>
    </div>
</div>
@endsection