<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;

class MtnGenerateKeys extends Command
{
    protected $signature = 'mtn:generate-keys';
    protected $description = 'Generate RSA1024 key pair for MTN payment integration';

    public function handle()
    {
        $res = openssl_pkey_new([
            'digest_alg' => 'sha256',
            'private_key_bits' => 1024,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        openssl_pkey_export($res, $privKey);
        $pubKeyDetails = openssl_pkey_get_details($res);
        $pubKey = $pubKeyDetails['key'];

        $dir = storage_path('app/mtn');
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        file_put_contents("$dir/private.pem", $privKey);
        file_put_contents("$dir/public.pem", $pubKey);

        $this->info("Keys generated:\n - private.pem\n - public.pem\nin storage/app/mtn/");
    }
}
