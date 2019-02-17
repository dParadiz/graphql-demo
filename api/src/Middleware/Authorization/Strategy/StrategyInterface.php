<?php
declare(strict_types=1);

namespace App\Middleware\Authorization\Strategy;

use Psr\Http\Message\RequestInterface;

interface StrategyInterface
{
    public function isAuthorized(RequestInterface $request): bool;
}