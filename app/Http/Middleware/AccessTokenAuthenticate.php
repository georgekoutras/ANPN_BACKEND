<?php


namespace App\Http\Middleware;


use App\Auth\AccessTokenGuard;

use App\Exceptions\TokenRefreshException;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;

class AccessTokenAuthenticate  extends Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param Closure $next
     * @param array $guards
     * @return mixed
     * @throws AuthenticationException
     * @throws TokenRefreshException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);
        return $next($request);
    }

    /**
     * @param $request
     * @param array $guards
     * @return void
     * @throws AuthenticationException
     * @throws TokenRefreshException
     */
    protected function authenticate($request, array $guards){

        if (empty($guards)) {
            return $this->auth->authenticate();
        }
        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()){
                return $this->auth->shouldUse($guard);
            }

        }
        $g = $this->auth->guard($guards[0]);

        if ($g instanceof AccessTokenGuard){
            $error = $g->getCheckResult();
            if ($error['error'] == 2){
                throw new TokenRefreshException('Token needs refresh.', $guards);
            }/*else if ($error['error'] == 1){
                throw new UserLockedException(trans('Your account has been locked. Please contact the administrator.'),$guards);
            }*/
            else {
                throw new AuthenticationException(trans('validation.unauthorized'), $guards);
            }
        }else {
            throw new AuthenticationException(trans('validation.unauthorized'), $guards);
        }

    }
}
