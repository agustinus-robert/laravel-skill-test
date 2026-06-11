<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait PostRepository
{
    public function paginateActive(int $perPage = 20): LengthAwarePaginator
    {
        return Post::with('user')
            ->active()
            ->latest()
            ->paginate($perPage);
    }

    public function findActive(Post $post): ?Post
    {
        return Post::active()
            ->with('user')
            ->whereKey($post->id)
            ->first();
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
