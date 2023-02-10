<?php
namespace App\Service;

class GenerateTokenService
{
    public function generateToken($email, $date)
    {
        $secret = $_ENV['KEY_TOKEN'];
        return hash_hmac('sha256', $email . $date, $secret);
    }
}
