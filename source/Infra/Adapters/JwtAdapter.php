<?php

declare(strict_types=1);

namespace Source\Infra\Adapters;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Source\App\Contracts\AuthToken;
use Source\Domain\ValueObjects\JwtPayload;
use Source\Domain\ValueObjects\Token;

final class JwtAdapter implements AuthToken
{
    private int $iat;
    private int $sub;
    private int $exp;

    /**
     * @throws Exception
     */
    public function tokenGenerate(int $userId): Token
    {
        $expires_at = strtotime(current_date_tz(date: null, addInterval: JWT_DURATION));

        $payload = array(
            "iat" => time(),
            "sub" => $userId,
            "exp" => $expires_at
        );

        return (new Token(JWT::encode($payload, JWT_SECRET_KEY, "HS256"), (int) $expires_at));
    }

    public function tokenValidate(string $token): bool
    {
        try {
            $jwt = JWT::decode($token, new Key(JWT_SECRET_KEY, "HS256"));
            $this->iat = $jwt->iat;
            $this->sub = $jwt->sub;
            $this->exp = $jwt->exp;

            return true;
        } catch (Exception $err) {
            // var_dump($err->getMessage());
            return false;
        }
    }

    public function getPayload(): JwtPayload
    {
        return new JwtPayload($this->iat, $this->sub, $this->exp);
    }
}
