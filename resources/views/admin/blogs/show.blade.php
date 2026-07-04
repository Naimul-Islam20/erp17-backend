@extends('layouts.admin', ['title' => 'Blog Details'])

@section('content')
<div class="header">
    <h1>Blog Details</h1>
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card" style="display:grid; gap:16px;">
    <div class="detail-list">
        <div class="detail-row">
            <div class="detail-label">ID</div>
            <div class="detail-value">{{ $blog->id }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Blog Title</div>
            <div class="detail-value">{{ $blog->title }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Category</div>
            <div class="detail-value">{{ $blog->category?->name ?? '-' }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Blocks</div>
            <div class="detail-value">{{ $blog->blocks->count() }}</div>
        </div>
    </div>

    <div class="builder-list" style="margin-top:0;">
        @forelse ($blog->blocks as $block)
            @if ($block->type === 'image')
                <div class="builder-card">
                    <div class="builder-card-header" style="margin-bottom:0;">
                        <div class="builder-card-title">Image</div>
                    </div>

                    <div class="block-preview" style="max-width: 420px; margin-top: 12px;">
                        <img src="{{ asset($block->image_path) }}" alt="Blog image">
                    </div>
                </div>
            @elseif ($block->type === 'point')
                @php
                    $pointInputs = json_decode((string) $block->point_body, true);
                    $pointInputs = is_array($pointInputs)
                        ? array_values(array_filter($pointInputs, fn ($item) => filled($item)))
                        : [trim((string) $block->point_body)];
                @endphp
                <div class="builder-card">
                    <div class="builder-card-header" style="margin-bottom:12px;">
                        <div class="builder-card-title">Point</div>
                    </div>

                    <div class="detail-list">
                        <div class="detail-row">
                            <div class="detail-label">Point Title</div>
                            <div class="detail-value">{{ $block->point_title }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Point Input</div>
                            <div class="detail-value">
                                @foreach ($pointInputs as $pointInput)
                                    <div @if (! $loop->first) style="margin-top: 10px;" @endif>{!! nl2br(e($pointInput)) !!}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="builder-card">
                    <div class="detail-list">
                        <div class="detail-row">
                            <div class="detail-label">Description</div>
                            <div class="detail-value">{!! nl2br(e($block->point_body)) !!}</div>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="empty-builder">No blog content blocks found.</div>
        @endforelse
    </div>
</div>
@endsection
