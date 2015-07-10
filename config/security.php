<?php

return [
    'content' => [

        'default' => 'global',

        'profiles' => [

            'global' => [
                'base-uri' => "'self'",
                'font-src' => [
                    "'self'",
                    'fonts.gstatic.com'
                ],
                'img-src' => "'self'",
                'script-src' => "'self'",
                'style-src' => [
                    "'self'",
                    "'unsafe-inline'",
                    'fonts.googleapis.com'
                ],
            ],

            'flickr' => [
                'img-src' => [
                    'https://*.staticflickr.com',
                ],
            ],

            'google' => [
                'img-src' => [
                    'https://maps.gstatic.com',
                    'https://csi.gstatic.com',
                    'https://maps.googleapis.com'
                ],
                'script-src' => [
                    'https://maps.googleapis.com',
                    'https://maps.gstatic.com',
                    "'unsafe-inline'",
                    "'unsafe-eval'",
                ],
            ],

        ],
    ],
];
