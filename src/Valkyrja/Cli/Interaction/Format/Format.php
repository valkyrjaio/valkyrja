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

namespace Valkyrja\Cli\Interaction\Format;

use Override;
use Valkyrja\Cli\Interaction\Format\Contract\FormatContract;

class Format implements FormatContract
{
    /**
     * @param non-empty-string $setCode   The set code
     * @param non-empty-string $unsetCode The unset code
     */
    public function __construct(
        protected string $setCode,
        protected string $unsetCode
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getSetCode(): string
    {
        return $this->setCode;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withSetCode(string $setCode): static
    {
        $new = clone $this;

        $new->setCode = $setCode;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getUnsetCode(): string
    {
        return $this->unsetCode;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withUnsetCode(string $unsetCode): static
    {
        $new = clone $this;

        $new->unsetCode = $unsetCode;

        return $new;
    }
}
