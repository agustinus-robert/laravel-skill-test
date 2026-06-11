<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
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

    public function validatedData(): array
    {
        return [
            ...$this->validated(),
            'is_draft' => (bool) $this->input('is_draft'),
        ];
    }
}
