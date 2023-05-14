<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\Request;

class TwoFA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */

    public $checkRoute = 'admin_2fa';
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if($request->route()->getName()!=$this->checkRoute&&Auth::user()->twofa_secret!=null && !session('2FAAuthFlag', false)){
            $ref = request()->headers->get('referer');
            if($ref==null||preg_match('~(top_up_balance|profile)$~i',$ref))
                $ref =route('admin_');
            return redirect(route($this->checkRoute).'?'.http_build_query(['refurl'=>$ref]));
        }
        return $next($request);
    }
}
