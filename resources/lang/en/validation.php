<?php

return [
    'required' => 'The :attribute field is required.',
    'email' => 'The :attribute must be a valid email address.',
    'unique' => 'The :attribute has already been taken.',
    'max' => [
        'string' => 'The :attribute may not be greater than :max characters.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'date' => 'The :attribute is not a valid date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'mimes' => 'The :attribute must be a file of type: :values.',
    'boolean' => 'The :attribute field must be true or false.',
    'attributes' => [
        'email' => 'email address',
        'password' => 'password',
        'password_confirmation' => 'password confirmation',
        'company_id' => 'company',
        'branch_id' => 'branch',
        'department_id' => 'department',
        'document_type_id' => 'document type',
        'expiry_date' => 'expiry date',
        'issue_date' => 'issue date',
        'file' => 'document file',
    ],
];
