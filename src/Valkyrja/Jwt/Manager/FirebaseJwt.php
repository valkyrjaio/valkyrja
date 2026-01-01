<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Jwt\Manager;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use OpenSSLAsymmetricKey;
use OpenSSLCertificate;
use Override;
use Valkyrja\Jwt\Enum\Algorithm;
use Valkyrja\Jwt\Manager\Contract\JwtContract as Contract;

class FirebaseJwt implements Contract
{
    /**
     * RsFirebaseJwt constructor.
     */
    public function __construct(
        protected OpenSSLAsymmetricKey|OpenSSLCertificate|string $encodeKey,
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
