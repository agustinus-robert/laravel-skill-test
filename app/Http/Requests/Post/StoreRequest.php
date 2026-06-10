<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'is_draft' => ['required', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function transform(): array
    {
        return [
            'title' => $this->input('title'),
            'content' => $this->input('content'),
            'is_draft' => (bool) $this->input('is_draft'),
            'published_at' => $this->input('published_at'),
        ];
    }
}
