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

namespace Valkyrja\Type\Uuid\Contract;

use Override;

/**
 * Interface UuidV4.
 *
 * @author Melech Mizrachi
 */
interface UuidV4 extends Uuid
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): string;

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): string;
}
