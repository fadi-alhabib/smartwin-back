<?php

namespace App\Services\Gateway\Mtn;



use Illuminate\Support\Str;

class SignatureService
{
    protected $privateKey;
    protected $publicKeyRaw;

    public function __construct()
    {
        $this->privateKey = file_get_contents(storage_path('app/mtn/private.pem'));
        $pub = file_get_contents(storage_path('app/mtn/public.pem'));
        // نزيل الـ headers والـ newlines
        $this->publicKeyRaw = Str::of($pub)
            ->replaceMatches('/-----(BEGIN|END)[\s\w]+-----/', '')
            ->replace("\n", '')
            ->trim();
    }

    /** توقيع Body */
    public function sign(array $body): string
    {
        $json = json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        // $hash = hash('sha256', $json, true);
        openssl_sign($json, $sig, openssl_pkey_get_private($this->privateKey), OPENSSL_ALGO_SHA256);

        return base64_encode($sig);
    }

    /** الإتصال الأول لتفعيل التيرمنال: public key */
    public function getPublicKeyParameter(): string
    {
        return $this->publicKeyRaw;
    }

    /** هاش + base64 لكود OTP */
    public function signOtpCode(string $code): string
    {
        $hash = hash('sha256', $code, true);
        return base64_encode($hash);
    }
}
