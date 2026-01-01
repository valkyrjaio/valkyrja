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

use Override;
use Valkyrja\Cli\Interaction\Argument\Contract\ArgumentContract as Contract;

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
    #[Override]
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withValue(string $value): static
    {
        $new = clone $this;

        $new->value = $value;

        return $new;
    }
}
