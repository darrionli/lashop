<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfEmailVerified
{
    /**
     * Handle an incoming request.
     * 验证用户邮箱之后才能访问系统，没有验证的用户将会重定向到 一个提示验证邮箱的页面
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->user()->email_verified){
            return redirect(route('email_verify_notice'));
        }
        return $next($request);
    }
}
