<?php declare(strict_types=1);

namespace Tests\Helpers;

class SessionCookie
{
    public function build(): array
    {
        $appKey        = str_replace('base64:', '', env('APP_KEY'));
        $cryptKey      = base64_decode($appKey);
        $cipher        = 'AES-256-CBC';
        $encrypter     = new \Illuminate\Encryption\Encrypter($cryptKey, $cipher);
        $cookiePrefix  = hash_hmac('sha1', 'laravel_session'.'v2', $encrypter->getKey());
        $cookieKey     = config('session.cookie');
        $cookiePayload = encrypt($cookiePrefix . '|' . session()->getId(), false);

        return [$cookieKey => $cookiePayload];
    }
}
