<?php

return [
    'title' => 'Users',
    'name' => 'Name',
    'email' => 'Email address',
    'role' => 'Role',
    'company' => 'Company',
    'branch' => 'Branch',
    'password_confirmation' => 'Confirm password',
    'messages' => [
        'created' => 'User created successfully.',
        'updated' => 'User updated successfully.',
        'deleted' => 'User deleted successfully.',
    ],
    'validation' => [
        'admin_not_allowed' => 'Only an administrator can create another administrator.',
    ],
];
