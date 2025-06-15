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

namespace Valkyrja\Dispatcher\Data;

use JsonException;
use Valkyrja\Dispatcher\Data\Contract\Dispatch as Contract;
use Valkyrja\Type\BuiltIn\Support\Arr;

use function get_object_vars;

/**
 * Abstract Class Dispatch.
 *
 * @author Melech Mizrachi
 */
abstract class Dispatch implements Contract
{
    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function __toString(): string
    {
        return Arr::toString($this->jsonSerialize());
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
