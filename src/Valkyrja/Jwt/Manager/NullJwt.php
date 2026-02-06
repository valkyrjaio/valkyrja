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

use JsonException;
use Override;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Type\Array\Factory\ArrayFactory;

class NullJwt implements JwtContract
{
    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function encode(array $payload): string
    {
        return ArrayFactory::toString($payload);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function decode(string $jwt): array
    {
        return ArrayFactory::fromString($jwt);
    }
}
