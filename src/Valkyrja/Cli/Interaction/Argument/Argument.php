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

namespace Valkyrja\Cli\Interaction\Argument;

use Valkyrja\Cli\Interaction\Argument\Contract\Argument as Contract;

/**
 * Class Argument.
 *
 * @author Melech Mizrachi
 */
class Argument implements Contract
{
    /**
     * @param non-empty-string $value The value
     */
    public function __construct(
        protected string $value,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function withValue(string $value): static
    {
        $new = clone $this;

        $new->value = $value;

        return $new;
    }
}
