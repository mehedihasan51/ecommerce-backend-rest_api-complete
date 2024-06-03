<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use App\Helpar\JWTToken;
use Illuminate\Http\Request;
use App\Helpar\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    public function UserLogin(Request $request):JsonResponse
    {

        try {
            // Validate the request
            $request->validate([
                'UserEmail' => 'required|email',
            ]);
    
            $UserEmail = $request->input('UserEmail');
            $OTP = rand(100000, 999999);
            $details = ['code' => $OTP];
    
            // Send the OTP email
            Mail::to($UserEmail)->send(new OTPMail($details));
    
            // Update or create the user record with the OTP
            User::updateOrCreate(
                ['email' => $UserEmail],
                ['otp' => $OTP]
            );
    
            return ResponseHelper::Out('success', 'A 6-digit OTP has been sent to your email address', 200);
    
        } catch (ValidationException $e) {
            // Handle validation errors
            return ResponseHelper::Out('fail', $e->errors(), 422);
    
        } catch (Exception $e) {
            // Log the exception
            Log::error('Error in UserLogin: ' . $e->getMessage());
    
            return ResponseHelper::Out('fail', $e->getMessage(), 500);
        }
}


 public function VerifyLogin(Request $request):JsonResponse
 {
    $UserEmail=$request->UserEmail;
    $OTP=$request->OTP;

    $verification= User::where('email',$UserEmail)->where('otp',$OTP)->first();

    $verification=User::where('email',$UserEmail)->where('otp',$OTP)->first();

    if($verification){
        $user=User::where('email',$UserEmail)->where('otp',$OTP)->update(['otp'=>'0']);

        $token=JWTToken::CreateToken($UserEmail,$verification->id);

        return ResponseHelper::Out('success','',200)->cookie('token', $token,60*24*30);
 }
 else{
    return ResponseHelper::Out('fail','null',401);
 }

}
function UserLogout(){
    return redirect('/userLoginPage')->cookie('token','',-1);
}
}
