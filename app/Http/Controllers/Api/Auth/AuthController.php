<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\SendOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Models\User;
use App\Services\Common\Contracts\ImageServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/auth')]
class AuthController extends Controller
{
    public function __construct(
        private readonly ImageServiceInterface $imageService,
    ) {}
    #[Post('/register')]
    public function register(RegisterRequest $request)
    {

        $data = $request->validated();
        $existingUser = User::where("phone", $data["phone"])->first();
        if ($existingUser) {
            return $this->failed(message: "يوجد حساب لهذا الرقم يرجى تسجيل الدخول");
        }
        $user = User::create([
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
        ]);

        return $this->success(message: 'User registered successfully.', data: $user, statusCode: 201);
    }

    #[Post('/send-otp')]
    public function sendOtp(SendOtpRequest $request)
    {
        $data = $request->validated();
        // $otp = 111111;
        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(15);

        $existingUser = User::where("phone", $data["phone"])->first();

        if (!$existingUser) {
            return $this->failed(message: "لا يوجد حساب لهذا الرقم يرجى انشاء حساب ثم تسجيل الدخول");
        }

        // Update or create user with OTP details
        $user = User::updateOrCreate(
            ['phone' => $data['phone']],
            ['otp' => $otp, 'otp_expires_at' => $expiresAt]
        );

        // Prepare SMS API parameters
        $smsParams = [
            'user_name' => "Smart Win1",
            'password' => "Pp@1234567",
            'template_code' => "Smart_T1",
            "param_list" => $otp,
            'sender' => "Smart Win",
            'to' => "963" . ltrim($data['phone'], 0) . ";",
        ];

        // Construct API URL with parameters
        $apiUrl = 'https://bms.syriatel.sy/API/SendTemplateSMS.aspx';

        try {
            // Send the SMS request
            $response = Http::withoutVerifying()->get($apiUrl, $smsParams);

            // Check if the request was successful
            if ($response->successful()) {
                return $this->success(message: 'OTP sent successfully.');
            } else {
                // Log the error and return failure
                Log::error("Failed to send OTP: " . $response->body());
                return $this->error(message: 'Failed to send OTP. Please try again later.');
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Handle connection timeout or network errors
            Log::error("OTP sending failed: " . $e->getMessage());
            return $this->failed('An error occurred while connecting to the SMS service.');
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::error("OTP sending failed: " . $e->getMessage());
            return $this->failed('An error occurred while sending OTP.');
        }
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

    #[Post('/google-redirect')]
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    #[Post('/google-callback')]
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $email = $googleUser->getEmail() ?? 'google_' . $googleUser->getId() . '@noemail.com';

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'full_name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'is_active' => true,
                    'provider' => 'google',
                ]
            );

            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->success(data: ['token' => $token, 'user' => $user]);
        } catch (\Throwable $th) {
            Log::emergency($th);
            return $this->failed(message: 'Google authentication failed.', statusCode: 500);
        }
    }

    #[Post('/profile-image', middleware: 'auth:sanctum')]
    public function uploadProfile(Request $request)
    {
        $user = $request->user();
        $validator = $request->validate([
            'image' => 'required|image|max:2024'
        ]);
        if ($request->file('image')) {
            $imagePath = $this->imageService->uploadImage($request->file('image'), '/users');
            $user->image = $imagePath;
            $user->save();
        }
        return $this->success(["image" => $imagePath]);
    }
}
