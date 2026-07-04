@extends('layouts.admin', ['title' => 'Newsletter Details'])

@section('content')
<div class="header">
    <h1>Newsletter Details</h1>
    <a href="{{ route('admin.newsletters.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card" style="max-width:920px; margin:0 auto; padding:24px;">
    <div style="display:grid; gap:8px; padding-bottom:18px; border-bottom:1px solid #e5edf6;">
        <div style="font-size:30px; font-weight:700; line-height:1.2; color:#0f172a;">{{ $newsletter->title }}</div>
        <div style="display:flex; flex-wrap:wrap; gap:8px 16px; color:#64748b; font-size:14px;">
            <span>{{ $newsletter->category?->name ?? '-' }}</span>
            <span>{{ $newsletter->published_at?->format('Y-m-d') ?? $newsletter->created_at?->format('Y-m-d') }}</span>
        </div>
    </div>

    <div style="display:grid; gap:18px; color:#334155; line-height:1.85; padding-top:18px;">
        @forelse ($newsletter->blocks as $block)
            @if ($block->type === 'image')
                <div style="display:flex; justify-content:center;">
                    <img src="{{ asset($block->image_path) }}" alt="Newsletter image" style="width:min(100%, 760px); display:block; border-radius:12px;">
                </div>
            @elseif ($block->type === 'point')
                @php
                    $pointInputs = json_decode((string) $block->point_body, true);
                    $pointInputs = is_array($pointInputs)
                        ? array_values(array_filter($pointInputs, fn ($item) => filled($item)))
                        : [trim((string) $block->point_body)];
                @endphp
                <div style="display:grid; gap:10px;">
                    @if (filled($block->point_title))
                        <h3 style="margin:0; color:#0f172a; font-size:20px; line-height:1.4;">{{ $block->point_title }}</h3>
                    @endif
                    <ul style="margin:0; padding-left:20px; display:grid; gap:6px;">
                        @foreach ($pointInputs as $pointInput)
                            <li>{!! nl2br(e($pointInput)) !!}</li>
                        @endforeach
                    </ul>
                </div>
            @elseif ($block->type === 'description')
                <div style="white-space:normal; word-break:break-word;">{!! nl2br(e($block->point_body)) !!}</div>
            @elseif ($block->type === 'h2')
                <h2 style="margin:6px 0 0; color:#0f172a; font-size:28px; line-height:1.3;">{{ $block->point_body }}</h2>
            @elseif ($block->type === 'h3')
                <h3 style="margin:6px 0 0; color:#0f172a; font-size:22px; line-height:1.4;">{{ $block->point_body }}</h3>
            @elseif ($block->type === 'h4')
                <h4 style="margin:6px 0 0; color:#0f172a; font-size:18px; line-height:1.5;">{{ $block->point_body }}</h4>
            @else
                <div style="white-space:normal; word-break:break-word;">{!! nl2br(e($block->point_body)) !!}</div>
            @endif
        @empty
            <div class="empty-builder">No newsletter content blocks found.</div>
        @endforelse
    </div>
</div>
@endsection
