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
