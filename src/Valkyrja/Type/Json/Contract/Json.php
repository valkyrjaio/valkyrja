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

namespace Valkyrja\Type\Json\Contract;

use Override;
use Valkyrja\Type\Contract\Type;

/**
 * Interface Json.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<array<string|int, mixed>>
 */
interface Json extends Type
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): array;

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): string;
}
