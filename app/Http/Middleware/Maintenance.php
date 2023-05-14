<?php

namespace App\Http\Middleware;

use App\Models\Settings;
use Closure;

class Maintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $iMaintenance = Settings::getValue('maintenance_status');
        if(!empty($iMaintenance))
            abort(503);
        else
            return $next($request);
    }
}
