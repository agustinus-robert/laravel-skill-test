<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests, PostRepository;

    public function index()
    {
        return response()->json(
            Post::with('user')
                ->active()
                ->latest()
                ->paginate(20)
        );
    }

    public function create()
    {
        $this->authorize('create', Post::class);

        return 'posts.create';
    }

    public function store(StoreRequest $request)
    {
        $post = $this->storePost(
            $request->validatedData(),
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

        return response()->json($post->load('user'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return 'posts.edit';
    }

    public function update(UpdateRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        return response()->json(
            $this->updatePost($post, $request->validatedData())
        );
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $this->deletePost($post);

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }
}
