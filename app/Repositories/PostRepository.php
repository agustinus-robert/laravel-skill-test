<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait PostRepository
{
    public function paginateActive(int $perPage = 20): LengthAwarePaginator
    {
        return Post::with('user')
            ->where('is_draft', false)
            ->where(function ($query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->latest()
            ->paginate($perPage);
    }

    public function findActive(Post $post): ?Post
    {
        if (
            $post->is_draft ||
            ($post->published_at && $post->published_at->isFuture())
        ) {
            return null;
        }

        return $post->load('user');
    }

    public function storePost(array $data, int $userId): Post
    {
        return Post::create(
            array_merge($data, [
                'user_id' => $userId,
            ])
        );
    }

    public function updatePost(Post $post, array $data): Post
    {
        $post->update(array_filter($data, function ($value) {
            return ! is_null($value);
        }));

        return $post->refresh();
    }

    public function deletePost(Post $post): bool
    {
        return $post->delete();
    }
}
