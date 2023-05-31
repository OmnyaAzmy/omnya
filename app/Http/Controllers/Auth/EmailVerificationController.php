<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\EmailVerificationNotification;
use Otp;

class EmailVerificationController extends Controller
{

    private $otp;
    public function __construct()
    {
           $this->otp = new Otp;
    }


    //في حالة انه استخدم الكود اكتر من مرة هيبعتله رسالة بكود جديد لان مينفعش يستخدم الكود اكتر من مرة
    public function sendEmailVerification (Request $request)
    {
       $request->user()->notify(new EmailVerificationNotification());
       $success['The new code has been sent to your Gmail, please go and check it'] = true;
       return response()->json($success, 200);
    }


    public function email_verification(EmailVerificationRequest $request)
    {

        $otp2 = $this->otp->validate($request->email, $request->otp);
       // dd($otp2);
        if(!$otp2->status){
            return response()->json(['error' => $otp2], 401);
        }

        $user = User::where('email', $request->email)->first();
         $user->update(['email_verified_at' =>now(), 'varifed'=> 1]);
        $success['email has been verified'] = true;
        return response()->json($success, 200);

    }



}
