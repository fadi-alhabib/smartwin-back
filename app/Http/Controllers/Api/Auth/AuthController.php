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
