<?php

namespace App\Http\Requests\Admin;

use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Blog::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:newsletter_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'blocks' => ['required', 'array', 'min:1'],
            'blocks.*.type' => ['required', 'in:image,point,description'],
            'blocks.*.image' => ['nullable', 'image', 'max:5120'],
            'blocks.*.point_title' => ['nullable', 'string', 'max:255'],
            'blocks.*.point_inputs' => ['nullable', 'array'],
            'blocks.*.point_inputs.*' => ['nullable', 'string'],
            'blocks.*.description_body' => ['nullable', 'string'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            foreach ((array) $this->input('blocks', []) as $index => $block) {
                $type = $block['type'] ?? null;

                if ($type === 'image' && ! data_get($this->file('blocks'), "{$index}.image")) {
                    $validator->errors()->add("blocks.$index.image", 'Image is required for this block.');
                }

                if ($type === 'point') {
                    if (! filled($block['point_title'] ?? null)) {
                        $validator->errors()->add("blocks.$index.point_title", 'Point title is required.');
                    }

                    $pointInputs = collect((array) ($block['point_inputs'] ?? []))
                        ->map(fn ($item) => trim((string) $item))
                        ->filter();

                    if ($pointInputs->isEmpty()) {
                        $validator->errors()->add("blocks.$index.point_inputs", 'At least one point input is required.');
                    }
                }

                if ($type === 'description' && ! filled($block['description_body'] ?? null)) {
                    $validator->errors()->add("blocks.$index.description_body", 'Description is required.');
                }
            }
        });
    }
}
