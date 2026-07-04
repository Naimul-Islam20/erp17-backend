<?php

namespace App\Http\Requests\Admin;

use App\Models\Education;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Education|null $education */
        $education = $this->route('education');

        return $education && ($this->user()?->can('update', $education) ?? false);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'youtube_link' => ['required', 'url', 'max:2048'],
        ];
    }
}
