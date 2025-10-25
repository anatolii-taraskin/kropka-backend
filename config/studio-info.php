<?php

return [
    'locales' => ['ru', 'en'],

    'shared' => [
        'phone' => [
            'rules' => ['required', 'string', 'max:255'],
            'type' => 'text',
            'required' => true,
        ],
        'email' => [
            'rules' => ['required', 'email', 'max:255'],
            'type' => 'email',
            'required' => true,
        ],
        'instagram_url' => [
            'rules' => ['nullable', 'url', 'max:255'],
            'type' => 'url',
            'required' => false,
        ],
        'facebook_url' => [
            'rules' => ['nullable', 'url', 'max:255'],
            'type' => 'url',
            'required' => false,
        ],
        'telegram_channel_url' => [
            'rules' => ['nullable', 'url', 'max:255'],
            'type' => 'url',
            'required' => false,
        ],
        'telegram_admin_url' => [
            'rules' => ['nullable', 'url', 'max:255'],
            'type' => 'url',
            'required' => false,
        ],
    ],

    'localized' => [
        'name' => [
            'rules' => ['required', 'string', 'max:255'],
            'type' => 'text',
            'required' => true,
        ],
        'address' => [
            'rules' => ['required', 'string', 'max:1000'],
            'type' => 'textarea',
            'required' => true,
        ],
    ],
];

