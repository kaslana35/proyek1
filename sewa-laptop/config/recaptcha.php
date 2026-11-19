<?php

return [

    // reCAPTCHA Site Key (masukan di .env: RECAPTCHA_SITE_KEY)
    'site_key' => env('RECAPTCHA_SITE_KEY', ''),

    // reCAPTCHA Secret Key (masukan di .env: RECAPTCHA_SECRET_KEY)
    'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),

    // Activate reCAPTCHA on these routes
    'enabled_on_routes' => [

        'login',
        'register',

    ],

    // reCAPTCHA v3 Score Threshold (untuk v3, 0.0 = robot, 1.0 = manusia)
    'score_threshold' => 0.5,

];