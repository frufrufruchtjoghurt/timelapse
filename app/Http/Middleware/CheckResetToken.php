<?php

namespace App\Http\Middleware;

use App\Models\PasswordResets;
use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckResetToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = explode('?', explode('/', $request->getRequestUri())[2])[0];
            $reset = PasswordResets::query()->where('email', '=', $request->email)
                ->where('token', '=', $token)->firstOrFail();

            if (Carbon::now()->subtract('hours', 17)->isAfter($reset->created_at)) {
                $reset->delete();
                throw new Exception('Token invalid!');
            }
        } catch (Exception $e) {
            return redirect()->route('password.token');
        }
        return $next($request);
    }
}
