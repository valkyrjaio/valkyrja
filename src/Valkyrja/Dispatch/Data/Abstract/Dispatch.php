<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Dispatch\Data\Abstract;

use JsonException;
use Override;
use Valkyrja\Dispatch\Data\Contract\DispatchContract;
use Valkyrja\Type\Array\Factory\ArrayFactory;

use function get_object_vars;

abstract class Dispatch implements DispatchContract
{
    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function __toString(): string
    {
        return ArrayFactory::toString($this->jsonSerialize());
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
