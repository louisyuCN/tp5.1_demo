<?php

namespace app\http\middleware;

use app\common\HttpResponse;
use think\facade\Cache;
use think\facade\Session;

class Auth
{
    public function handle($request, \Closure $next)
    {
        $username = Session::get('username');
        $code = Session::get('code');
        if (!isset($username) || !isset($code))
            return HttpResponse::fail('未登录!');
        return $next($request);
    }
}
