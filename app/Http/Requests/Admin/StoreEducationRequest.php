<?php

namespace App\Http\Requests\Admin;

use App\Models\Education;
use Illuminate\Foundation\Http\FormRequest;

class StoreEducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Education::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:newsletter_categories,id'],
            'youtube_link' => ['required', 'url', 'max:2048'],
        ];
    }
}
