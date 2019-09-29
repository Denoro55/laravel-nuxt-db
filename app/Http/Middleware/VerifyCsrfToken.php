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

//    const SITE_IP = 'http://laravel-auth';
    const SITE_IP = 'http://188.225.75.245';

    protected $except = [
        SITE_IP.'/api/articles',
        SITE_IP.'/api/comments',
        SITE_IP.'/api/user',
        SITE_IP.'/api/message'
    ];
}
