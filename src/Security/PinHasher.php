<?php

namespace App\Security;

final class PinHasher
{
    public function __construct(
        private string $pepper
    ) {}

    public function hash(string $pin): string
    {
        return hash('sha256', $pin . $this->pepper);
    }

    public function verify(string $pin, string $hash): bool
    {
        return hash_equals($hash, $this->hash($pin));
    }
}