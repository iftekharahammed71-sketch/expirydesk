<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeDocumentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('document')) ?? false;
    }

    public function rules(): array
    {
        $document = $this->route('document');

        return [
            'document_type_id' => ['required', 'integer', Rule::exists('document_types', 'id')->where(function ($query) use ($document): void {
                $query->where('is_active', true)->where(function ($scope) use ($document): void {
                    $scope->whereNull('company_id')->orWhere('company_id', $document->employee->company_id);
                });
            })],
            'document_number' => ['nullable', 'string', 'max:150'],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['required', 'date', 'after_or_equal:issue_date'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ];
    }
}
