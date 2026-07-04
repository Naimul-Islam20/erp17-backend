<?php

namespace App\Http\Requests\Admin;

use App\Models\Newsletter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreNewsletterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Newsletter::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:newsletter_categories,id'],
            'blocks' => ['required', 'array', 'min:1'],
            'blocks.*.type' => ['required', 'in:image,point,description,h2,h3,h4'],
            'blocks.*.image' => ['nullable', 'image', 'max:4096'],
            'blocks.*.point_title' => ['nullable', 'string', 'max:255'],
            'blocks.*.point_inputs' => ['nullable', 'array'],
            'blocks.*.point_inputs.*' => ['nullable', 'string'],
            'blocks.*.description_body' => ['nullable', 'string'],
            'blocks.*.heading_text' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $types = collect((array) $this->input('blocks', []))
                ->pluck('type')
                ->filter()
                ->values();

            if (! $types->contains('image')) {
                $validator->errors()->add('blocks', 'At least one image block is required.');
            }

            if (! $types->contains('description')) {
                $validator->errors()->add('blocks', 'At least one description block is required.');
            }

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

                if (in_array($type, ['h2', 'h3', 'h4'], true) && ! filled($block['heading_text'] ?? null)) {
                    $validator->errors()->add("blocks.$index.heading_text", 'Heading text is required.');
                }
            }
        });
    }
}
