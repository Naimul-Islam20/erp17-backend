<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->input('password') === 'Password' && blank($this->input('password_confirmation'))) {
            $this->merge([
                'password' => null,
                'password_confirmation' => null,
            ]);
        }
    }

    public function authorize(): bool
    {
        /** @var User|null $target */
        $target = $this->route('user');

        return $target && ($this->user()?->can('update', $target) ?? false);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var User $target */
        $target = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($target->id)],
            'password' => ['nullable', 'confirmed', 'string', 'min:6'],
            'role' => ['required', 'in:admin,super_admin'],
        ];
    }
}
