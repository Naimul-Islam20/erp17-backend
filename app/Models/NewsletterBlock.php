<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsletterBlock extends Model
{
    protected $fillable = [
        'newsletter_id',
        'type',
        'point_title',
        'point_body',
        'image_path',
        'sort_order',
    ];

    public function newsletter(): BelongsTo
    {
        return $this->belongsTo(Newsletter::class);
    }
}
