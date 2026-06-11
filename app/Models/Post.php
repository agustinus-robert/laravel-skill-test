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

    protected function casts(): array
    {
        return [
            'is_draft' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusAttribute(): string
    {
        if ($this->is_draft) {
            return 'Draft';
        }

        if ($this->published_at?->isFuture()) {
            return 'Scheduled';
        }

        return 'Published';
    }

    public function scopePublished($query)
    {
        return $query
            ->where('is_draft', false);
    }
}
