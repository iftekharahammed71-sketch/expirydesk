<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WhiteLabelUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('company')) ?? false;
    }

    public function rules(): array
    {
        return [
            'company_display_name' => ['required', 'string', 'max:150'],
            'brand_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'mail_from_name' => ['required', 'string', 'max:150'],
            'mail_from_address' => ['required', 'email:rfc', 'max:255'],
            'logo' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
        ];
    }
}
