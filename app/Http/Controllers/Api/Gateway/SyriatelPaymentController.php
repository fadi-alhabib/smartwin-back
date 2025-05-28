<?php


namespace App\Http\Controllers\Api\Gateway;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\Syriatel\ConfirmPaymentRequest;
use App\Http\Requests\Payment\Syriatel\RequestPaymentRequest;
use App\Models\SyriatelPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/syriatel-payments')]
class SyriatelPaymentController extends Controller
{
    private $baseUrl;
    private $merchantNumber;
    private $username;
    private $password;

    public function __construct()
    {
        $this->baseUrl = config("syriatel.base_url");
        $this->merchantNumber = config("syriatel.merchant");
        $this->username = config("syriatel.username");
        $this->password = config("syriatel.password");
    }

    #[Post("/request", middleware: "auth:sanctum")]
    public function requestPayment(RequestPaymentRequest $request)
    {
        $user = $request->user();
        $res = Http::post("{$this->baseUrl}/getToken", data: [
            "username" => $this->username,
            "password" => $this->password,
        ]);
        $token = $res->json()["token"];
        $transactionId = Str::uuid()->toString();
        $body = [
            "customerMSISDN" => $request->customerMSISDN,
            "merchantMSISDN" => $this->merchantNumber,
            "amount" => $request->amount,
            "transactionID" => $transactionId,
            "token" => $token,
        ];
        $resPayment = Http::post("{$this->baseUrl}/paymentRequest", data: $body);
        $resJson = $resPayment->json();
        $errorCode = $resJson["errorCode"];
        if ($errorCode == 0) {
            SyriatelPayment::create([...$body, "user_id" => $user->id]);
            return $this->success();
        } else if ($errorCode == -4) {
            return $this->failed("رقم خاطئ");
        } else if ($errorCode == -105) {
            return $this->failed("ليس لديك حساب في Syriatel cash");
        } else {
            return $this->failed("حدث خطأ ما يرجى إعادة المحاولة");
        }
    }

    #[Post("/confirm", middleware: "auth:sanctum")]
    public function confirmPayment(ConfirmPaymentRequest $request)
    {
        $payment = SyriatelPayment::where("user_id", $request->user()->id)->latest()->first();
        $body =  [
            "OTP" => $request->otp,
            "merchantMSISDN" => $this->merchantNumber,
            "transactionID" => $payment->transactionID,
            "token" => $payment->token,
        ];
        $resConfirm = Http::post("/paymentConfirmation", data: $body);
        $errorCode = $resConfirm["errorCode"];
        if ($errorCode == 0) {
            $payment->complete = true;
            $payment->save();
            $user = $request->user();
            if ($payment->amount == 1200 || $payment->amount == 1200000) {
                $user->points += 100;
            } elseif ($payment->amount == 2200 || $payment->amount == 2200000) {
                $user->points += 300;
            } elseif ($payment->amount == 5300 || $payment->amount == 5300000) {
                $user->points += 500;
            } elseif ($payment->amount == 10500 || $payment->amount == 10500000) {
                $user->points += 1000;
            }
            $user->save();
            return $this->success();
        } else if ($errorCode == -6 || $errorCode == -8) {
            return $this->failed("ليس لديك حساب Syriatel Cash او حسابك غير مفعل");
        } else if ($errorCode == -13) {
            return $this->failed("ليس لديك رصيد كافي في حسابك");
        } else if ($errorCode == -17) {
            return $this->failed("حسابك سيتعدى الحد اليومي المسموح به");
        } else if ($errorCode == -96) {
            return $this->failed("كود المدخل غير صحيح");
        } else if ($errorCode == -103) {
            return $this->failed("لقد حدثت مشكلة من جهة سيرياتيل الرجاء التواصل مع الشركة لاسترداد الاموال");
        } else if ($errorCode == -104) {
            return $this->failed("انتهت صلاحية كود التفعيل الرجاء اعادة طلبه");
        } else {
            return $this->failed("حدث خطأ ما يرجى إعادة المحاولة");
        }
    }

    #[Get("resend-otp", middleware: "auth:sanctum")]
    public function resendOtp(Request $request)
    {
        $payment = SyriatelPayment::where("user_id", $request->user()->id)->latest()->first();
        $res = Http::post("/resendOTP", [
            "merchantMSISDN" => $this->merchantNumber,
            "transactionID" => $payment->transactionID,
            "token" => $payment->token,
        ]);
        $errorCode = $res["errorCode"];
        if ($errorCode == 0) {
            return $this->success();
        } else {
            return $this->failed("لقد حدث خطأ يرجى إعادة المحاولة");
        }
    }
}
