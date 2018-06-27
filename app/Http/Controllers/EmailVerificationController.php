<?php

namespace App\Http\Controllers;

use Cache;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use App\Notifications\EmailVerificationNotification;
use Mail;

class EmailVerificationController extends Controller
{
    public function verify(Request $request)
    {
        $email = $request->input('email');
        $token = $request->input('token');
        // 验证连接是否合法
        if(!$email || !$token){
            throw new Exception('验证连接不正确');
        }
        // 从缓存中读取数据，从Url中获取token和缓存中的值作对比
        // 如果缓存不存在或者返回的值与url中的token不一致抛出异常
        if($token != Cache::get('email_verification_'.$email)){
            throw new Exception('验证链接不正确或已过期');
        }
        // 根据邮箱从数据库中获取对应的用户
        if(!$user = User::where('email', $email)->first()){
            throw new Exception('用户不存在');
        }
        Cache::forget('email_verification_'.$email);
        $user->update(['email_verified' => true]);
        return view('pages.success', ['msg'=>'邮箱验证成功']);
    }

    // 发送邮件
    public function send(Request $request)
    {
        $user = $request->user();
        // 判断用户是否已经激活
        if($user->email_verified){
            throw new Exception('你已经通过邮箱验证了');
        }
        // 调用notify()方法用来发送定义好的通知类
        $user->notify(new EmailVerificationNotification());
        return view('pages.success', ['msg'=>'邮件发送成功']);
    }
}
