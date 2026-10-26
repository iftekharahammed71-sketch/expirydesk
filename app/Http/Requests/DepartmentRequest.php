<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $departmentId = $this->route('department')?->id;
        $companyId = $this->user()?->isAdmin() ? $this->integer('company_id') : $this->user()?->company_id;

        return [
            'company_id' => [$this->user()?->isAdmin() ? 'required' : 'nullable', 'integer', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:150', Rule::unique('departments', 'name')->where(fn ($query) => $query->where('company_id', $companyId))->ignore($departmentId)],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
