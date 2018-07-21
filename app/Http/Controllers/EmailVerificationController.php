<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Cache;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;	
use Mail;

class EmailVerificationController extends Controller
{
    public function verify(Request $request)
    {
    	$email = $request->input('email');
    	$token = $request->input('token');
    	if (!$email || !$token) {
    		throw new Exception('验证链接不正确');
    		
    	}
    	if ($token !=Cache::get('email_verification_'.$email)) {
    		throw new Exception('验证链接不正确或已过期');
    		
    	}
    	Cache::forget('email_verification_'.$email);
    	$user->update(['email_verified'=>true]);
    	return view('pages.success',['msg' =>'邮箱验证成功']);
    }
    public function send(Request $request)
    {
    	$user = $request->user();
    	if ($user->email_verified) {
    		throw new Exception('你已经验证过邮箱了');
    		
    	}
    	$user->notify(new EmailVerificationNotification());
    	return view('pages.success',['mag'=>'邮件发送成功']);
    }
}
