<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    protected $table = 'educations';

    protected $fillable = [
        'title',
        'category_id',
        'youtube_link',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsletterCategory::class, 'category_id');
    }
}
