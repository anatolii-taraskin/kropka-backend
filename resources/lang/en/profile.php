<?php

return [
    'information' => [
        'title' => 'Profile Information',
        'description' => "",
        'success' => 'Your profile changes have been saved successfully.',
        'fields' => [
            'name' => 'Name',
            'email' => 'Email',
        ],
        'email_unverified' => 'Your email address is unverified.',
        'resend_verification' => 'Click here to re-send the verification email.',
        'verification_link_sent' => 'A new verification link has been sent to your email address.',
        'submit' => 'Save',
    ],

    'password' => [
        'title' => 'Update Password',
        'description' => 'Ensure your account is using a long, random password to stay secure.',
        'success' => 'Your password has been updated successfully.',
        'fields' => [
            'current_password' => 'Current Password',
            'password' => 'New Password',
            'password_confirmation' => 'Confirm Password',
        ],
        'submit' => 'Save',
    ],

];
