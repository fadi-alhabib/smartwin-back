<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\SendOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/auth')]
class AuthController extends Controller
{
    #[Post('/register')]
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $user = User::create([
                'full_name' => $data['full_name'],
                'phone' => $data['phone'],
            ]);

            return $this->success(message: 'User registered successfully.', data: $user, statusCode: 201);
        } catch (\Throwable $th) {
            Log::emergency($th);
            return $this->failed($th, 500);
        }
    }
    #[Post('/send-otp')]
    public function sendOtp(SendOtpRequest $request)
    {
        $data = $request->validated();


        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(15);

        $user = User::updateOrCreate(
            ['phone' => $data['phone']],
            ['otp' => $otp, 'otp_expires_at' => $expiresAt]
        );

        // Send OTP using Twilio
        // $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
        // $twilio->messages->create(
        //     $user->phone,
        //     [
        //         'from' => env('TWILIO_PHONE_NUMBER'),
        //         'body' => "Your OTP is $otp. It expires in 10 minutes."
        //     ]
        // );

        return $this->success(message: 'OTP sent successfully.');
    }
    #[Post('/verify-otp')]
    public function verifyOtp(VerifyOtpRequest $request)
    {
        $data = $request->validated();

        $user = User::where('phone', $data['phone'])->first();

        if (!$user || $user->otp !== $data['otp'] || Carbon::parse($user->otp_expires_at)->isPast()) {
            return $this->failed(message: 'Invalid or expired OTP.', statusCode: 400);
        }

        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success(data: ['token' => $token, 'user' => $user]);
    }
}
