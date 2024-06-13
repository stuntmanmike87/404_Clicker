<?php

declare(strict_types=1);

namespace App\Service;

use Nette\Utils\Strings;

final class JWTService
{
    /**
     * @param array<string> $header
     * @param array<string> $payload
     */
    public function generate(array $header, array $payload, string $secret, int $validity = 10800): string
    {
        if ($validity > 0) {
            $now = new \DateTimeImmutable();
            $exp = $now->getTimestamp() + $validity;

            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $exp;
        }

        $base64Header = base64_encode((string) json_encode($header, JSON_THROW_ON_ERROR));
        $base64Payload = base64_encode((string) json_encode($payload, JSON_THROW_ON_ERROR));

        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        $secret = base64_encode($secret);
        $signature = hash_hmac('sha256', $base64Header.'.'.$base64Payload, $secret, true);

        $base64Signature = base64_encode($signature);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

        return $base64Header.'.'.$base64Payload.'.'.$base64Signature;
    }

    public function isValid(string $token): bool
    {
        /* return 1 === preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
            $token
        ); */        
        // /** @var null|array $result */
        /* $result = */ Strings::match(
            $token,
            '#^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$#',
        );

        return true;
    }

    /** @return  array<string> $payload */
    public function getPayload(string $token): array
    {
        $array = explode('.', $token);

        /** @var array<string> $payload */
        $payload = json_decode((string) base64_decode($array[1], true), true, 512, JSON_THROW_ON_ERROR);

        return $payload;
    }

    /** @return  array<string> $header */
    public function getHeader(string $token): array
    {
        $array = explode('.', $token);

        /** @var array<string> $header */
        $header = json_decode((string) base64_decode($array[0], true), true, 512, JSON_THROW_ON_ERROR);

        return $header;
    }

    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);

        $now = new \DateTimeImmutable();

        return $payload['exp'] < $now->getTimestamp();
    }

    public function check(string $token, string $secret): bool
    {
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        $verifToken = $this->generate($header, $payload, $secret, 0);

        return $token === $verifToken;
    }
}
