<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Employee::class) ?? false;
    }

    public function rules(): array
    {
        $companyId = $this->user()?->isAdmin() ? $this->integer('company_id') : $this->user()?->company_id;

        return [
            'company_id' => [$this->user()?->isAdmin() ? 'required' : 'nullable', 'integer', 'exists:companies,id'],
            'branch_id' => [
                'required',
                'integer',
                Rule::exists('branches', 'id')->where(fn ($query) => $query->where('company_id', $companyId)->where('is_active', true)),
            ],
            'department_id' => [
                'nullable',
                'integer',
                Rule::exists('departments', 'id')->where(fn ($query) => $query->where('company_id', $companyId)->where('is_active', true)),
            ],
            'employee_number' => ['required', 'string', 'max:80'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'job_title' => ['nullable', 'string', 'max:150'],
            'status' => ['required', Rule::in(array_map(fn ($status) => $status->value, \App\Enums\EmployeeStatus::cases()))],
        ];
    }
}
