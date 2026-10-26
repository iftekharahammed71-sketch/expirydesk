<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReminderSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('company')) ?? false;
    }

    public function rules(): array
    {
        return [
            'lead_times' => ['nullable', 'array', 'max:10'],
            'lead_times.*' => ['required', 'integer', 'min:1', 'max:365', 'distinct'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $value = $this->input('lead_times', []);

        if (is_string($value)) {
            $value = array_values(array_filter(array_map('trim', explode(',', $value)), fn ($item) => $item !== ''));
        }

        $this->merge(['lead_times' => $value]);
    }
}
