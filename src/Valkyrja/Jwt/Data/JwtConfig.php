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

namespace Valkyrja\Jwt\Data;

use Valkyrja\Jwt\Data\Contract\JwtConfigContract;
use Valkyrja\Jwt\Enum\Algorithm;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Jwt\Manager\FirebaseJwt;

class JwtConfig implements JwtConfigContract
{
    /**
     * @param class-string<JwtContract> $defaultJwt The jwt manager to use by default
     * @param Algorithm                 $algorithm  The algorithm that signs a token
     */
    public function __construct(
        public readonly string $defaultJwt = FirebaseJwt::class,
        public readonly Algorithm $algorithm = Algorithm::HS256,
    ) {
    }
}
