<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogBlock extends Model
{
    protected $fillable = [
        'blog_id',
        'type',
        'point_title',
        'point_body',
        'image_path',
        'sort_order',
    ];

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }
}
