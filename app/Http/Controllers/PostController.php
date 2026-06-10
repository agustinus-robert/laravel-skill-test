<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Post;
use App\Repositories\PostRepository;

class PostController extends Controller
{
    use PostRepository;

    public function index()
    {
        return response()->json(
            $this->paginateActive(20)
        );
    }

    public function create()
    {
        return 'posts.create';
    }

    public function store(StoreRequest $request)
    {
        $post = $this->create(
            $request->transform(),
            $request->user()->id
        );

        return response()->json($post, 201);
    }

    public function show(Post $post)
    {
        $post = $this->findActive($post);

        if (! $post) {
            abort(404);
        }

        return response()->json($post);
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return 'posts.edit';
    }

    public function update(UpdateRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $post = $this->update(
            $post,
            $request->transform()
        );

        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $this->delete($post);

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }
}
