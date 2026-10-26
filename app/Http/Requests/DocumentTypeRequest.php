<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $typeId = $this->route('document_type')?->id;
        $companyId = $this->user()?->isAdmin() ? $this->input('company_id') : $this->user()?->company_id;

        return [
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'alpha_dash', 'max:80', Rule::unique('document_types', 'code')->where(fn ($query) => $query->where('company_id', $companyId))->ignore($typeId)],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
