<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class AdminPostController extends Controller
{
    use AuthorizesRequests, PostRepository;

    public function index()
    {
        return Inertia::render('admin/post/index', [
            'posts' => $this->paginateActive(20),
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/post/upsert', [
            'post' => null,
            'mode' => 'create',
        ]);
    }

    public function store(StoreRequest $request)
    {
        $this->storePost(
            $request->transform(),
            $request->user()->id
        );

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Data added successfully');
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return Inertia::render('admin/post/upsert', [
            'post' => $post,
            'mode' => 'edit',
        ]);
    }

    public function update(UpdateRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $this->updatePost(
            $post,
            $request->transform()
        );

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Data updated successfully');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $this->deletePost($post);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Data deleted successfully');
    }
}
