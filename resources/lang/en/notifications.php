<?php

return [
    'command' => [
        'sent' => ':count reminder notification(s) sent.',
    ],
    'document_expiry' => [
        'subject' => 'Document expiry reminder for :employee',
        'greeting' => 'Document expiry reminder',
        'line' => ':document for :employee expires on :date, which is :days day(s) away.',
        'action' => 'View employee documents',
        'footer' => 'Please review the document and arrange renewal if required.',
    ],
];
