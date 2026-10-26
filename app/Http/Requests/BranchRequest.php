<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $branchId = $this->route('branch')?->id;
        $companyId = $this->user()?->isAdmin() ? $this->integer('company_id') : $this->user()?->company_id;

        return [
            'company_id' => [$this->user()?->isAdmin() ? 'required' : 'nullable', 'integer', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:40', Rule::unique('branches', 'code')->where(fn ($query) => $query->where('company_id', $companyId))->ignore($branchId)],
            'address' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
