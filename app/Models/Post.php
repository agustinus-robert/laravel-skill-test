<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'is_draft',
        'published_at',
    ];

    protected $appends = [
        'status',
    ];

    protected $casts = [
        'is_draft' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusAttribute(): string
    {
        if ($this->is_draft) {
            return 'draft';
        }

        if ($this->published_at && $this->published_at->gt(now())) {
            return 'scheduled';
        }

        return 'published';
    }

    public function scopeActive($query)
    {
        return $query
            ->where('is_draft', false)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
}
