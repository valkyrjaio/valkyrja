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

namespace Valkyrja\Tests\Fixtures\Jwt\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Jwt\Data\Contract\JwtConfigContract;
use Valkyrja\Jwt\Data\Contract\JwtEdDsaConfigContract;
use Valkyrja\Jwt\Data\Contract\JwtHsConfigContract;
use Valkyrja\Jwt\Data\Contract\JwtRsConfigContract;
use Valkyrja\Jwt\Enum\Algorithm;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Jwt\Manager\NullJwt;

/**
 * An application config that implements every jwt contract at once.
 *
 * The algorithm contracts prefix each property with the algorithm name, so one
 * class can carry the keys for several algorithms without a name collision.
 */
final class JwtConfigFixture extends Config implements JwtConfigContract, JwtHsConfigContract, JwtRsConfigContract, JwtEdDsaConfigContract
{
    /**
     * @param class-string<JwtContract> $defaultJwt
     * @param non-empty-string          $hsKey
     * @param non-empty-string          $rsPrivateKey
     * @param non-empty-string          $rsPublicKey
     * @param non-empty-string          $edDsaPrivateKey
     * @param non-empty-string          $edDsaPublicKey
     */
    public function __construct(
        public string $defaultJwt = NullJwt::class,
        public Algorithm $algorithm = Algorithm::HS256,
        public string $hsKey = 'test-key',
        public string $rsPrivateKey = 'test-rs-private',
        public string $rsPublicKey = 'test-rs-public',
        public string $edDsaPrivateKey = 'test-eddsa-private',
        public string $edDsaPublicKey = 'test-eddsa-public',
    ) {
        parent::__construct();
    }
}
