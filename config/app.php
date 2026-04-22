<?php
return [
    'auth' => \Src\Auth\Auth::class,
    'identity' => \Model\User::class,

    'routeMiddleware' => [
        'auth' => \Middlewares\AuthMiddleware::class,
    ],

    'validators' => [
        'required' => \Validators\RequireValidator::class,
        'unique'   => \Validators\UniqueValidator::class,
        'email'    => \Validators\EmailValidator::class,
        'min'      => \Validators\MinValidator::class,
        'date'     => \Validators\DateValidator::class
    ],

    'routeAppMiddleware' => [
        'csrf' => \Middlewares\CSRFMiddleware::class,
        'trim' => \Middlewares\TrimMiddleware::class,
        'specialChars' => \Middlewares\SpecialCharsMiddleware::class,
    ],
];