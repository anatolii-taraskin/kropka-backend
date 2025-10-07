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

    'deletion' => [
        'title' => 'Delete Account',
        'description' => 'Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.',
        'trigger' => 'Delete Account',
        'modal_title' => 'Are you sure you want to delete your account?',
        'modal_description' => 'Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.',
        'password_label' => 'Password',
        'cancel' => 'Cancel',
        'submit' => 'Delete Account',
    ],
];
