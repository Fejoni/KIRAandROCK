<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class CheckVerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $oUser = $request->user('api');
        if (!$oUser || ($oUser->email_verified_at === null)) {
            abort(403, 'Your email address is not verified.');
        }

        return $next($request);
    }
}
