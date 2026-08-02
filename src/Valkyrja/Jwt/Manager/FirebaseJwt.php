<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Jwt\Manager;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use OpenSSLAsymmetricKey;
use OpenSSLCertificate;
use Override;
use SensitiveParameter;
use Valkyrja\Jwt\Enum\Algorithm;
use Valkyrja\Jwt\Manager\Contract\JwtContract;

class FirebaseJwt implements JwtContract
{
    public function __construct(
        #[SensitiveParameter]
        protected OpenSSLAsymmetricKey|OpenSSLCertificate|string $encodeKey,
        #[SensitiveParameter]
        protected OpenSSLAsymmetricKey|OpenSSLCertificate|string $decodeKey,
        protected Algorithm $algorithm,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function encode(array $payload): string
    {
        return JWT::encode($payload, $this->encodeKey, $this->algorithm->name);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function decode(string $jwt): array
    {
        return (array) JWT::decode($jwt, new Key($this->decodeKey, $this->algorithm->name));
    }
}
