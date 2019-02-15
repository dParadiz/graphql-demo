<?php

namespace App\Middleware\Authorization\Strategy;

use Firebase\JWT\JWT as JWTService;
use Psr\Http\Message\RequestInterface;

class Jwt implements StrategyInterface
{
    private $key;
    private $allowedAlgorithms = [];

    public function __construct(string $key, array $allowedAlgorithms)
    {
        $this->key = $key;
        $this->allowedAlgorithms = $allowedAlgorithms;
    }

    public function isAuthorized(RequestInterface $request): bool
    {
        $token = '';
        foreach ($request->getHeader('Authorization') as $authHeader) {
            if (strpos($authHeader, 'Bearer ') !== false) {
                $token = str_replace('Bearer ', '', $authHeader);
                break;
            }
        };

        try {
            JWTService::decode($token, $this->key, $this->allowedAlgorithms);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}