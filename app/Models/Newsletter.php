<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Newsletter extends Model
{
    protected $fillable = [
        'title',
        'category_id',
        'published_at',
        'image_path',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsletterCategory::class, 'category_id');
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(NewsletterBlock::class)->orderBy('sort_order')->orderBy('id');
    }
}
