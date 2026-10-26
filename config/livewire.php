<?php

return [
    'class_namespace' => 'App\\Livewire',
    'view_path' => resource_path('views/livewire'),
    'layout' => 'layouts.app',
    'make_command' => [
        'type' => 'class',
        'emoji' => false,
    ],
    'inject_assets' => true,
    'navigate' => [
        'show_progress_bar' => true,
        'progress_bar_color' => '#2563eb',
    ],
    'temporary_file_upload' => [
        'disk' => 'local',
        'rules' => ['required', 'file', 'max:10240'],
        'directory' => 'livewire-tmp',
        'middleware' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4', 'mov', 'avi', 'wmv', 'mp3', 'm4a', 'jpg', 'jpeg', 'mpga', 'webp',
        ],
        'max_upload_time' => 5,
        'cleanup' => true,
    ],
    'render_on_redirect' => false,
    'legacy_model_binding' => false,
    'pagination_theme' => 'tailwind',
    'pagination' => [
        'theme' => 'tailwind',
    ],
];
