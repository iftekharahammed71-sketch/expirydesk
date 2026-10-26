<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $target = $this->route('user');
        $companyId = $this->user()?->isAdmin() ? $this->input('company_id') : $this->user()?->company_id;

        return [
            'company_id' => [$this->user()?->isAdmin() ? 'required_unless:role,admin' : 'nullable', 'integer', 'exists:companies,id'],
            'branch_id' => ['required_if:role,manager', 'nullable', 'integer', Rule::exists('branches', 'id')->where(fn ($query) => $query->where('company_id', $companyId))],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($target?->id)],
            'role' => ['required', Rule::enum(UserRole::class)],
            'password' => [$target ? 'nullable' : 'required', 'string', 'min:12', 'confirmed'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function after(): array
    {
        return [function ($validator): void {
            if ($this->user()?->isHr() && $this->string('role')->value() === UserRole::ADMIN->value) {
                $validator->errors()->add('role', __('users.validation.admin_not_allowed'));
            }

            if ($this->user()?->isHr() && $this->filled('company_id') && (int) $this->input('company_id') !== $this->user()->company_id) {
                $validator->errors()->add('company_id', __('validation.exists', ['attribute' => __('users.company')]));
            }
        }];
    }
}
