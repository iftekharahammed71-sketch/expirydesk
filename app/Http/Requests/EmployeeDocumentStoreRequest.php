<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeDocumentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\EmployeeDocument::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'document_type_id' => ['required', 'integer', Rule::exists('document_types', 'id')->where(function ($query): void {
                $query->where('is_active', true)->where(function ($scope): void {
                    $scope->whereNull('company_id')->orWhere('company_id', $this->route('employee')->company_id);
                });
            })],
            'document_number' => ['nullable', 'string', 'max:150'],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['required', 'date', 'after_or_equal:issue_date'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ];
    }
}
