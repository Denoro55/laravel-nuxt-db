<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'https://nuxt-app-db.herokuapp.com/api/articles',
        'https://nuxt-app-db.herokuapp.com/api/comments',
        'https://nuxt-app-db.herokuapp.com/api/user',
        'https://nuxt-app-db.herokuapp.com/api/message'
    ];
}
