<?php

namespace App\Http\Middleware;

use App\SettingsModel;
use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }

        $settings = SettingsModel::find(1);
        if ($settings['active_system'] == 'N' && Auth::user()['user_type'] != 1) {
            return redirect()->to('close');
        }

        return $next($request);
    }
}
