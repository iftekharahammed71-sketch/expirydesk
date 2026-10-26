<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $companyId = $this->route('company')?->id;

        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'alpha_dash', 'max:150', Rule::unique('companies', 'slug')->ignore($companyId)],
            'timezone' => ['required', 'timezone'],
            'status' => ['required', 'boolean'],
        ];
    }
}
