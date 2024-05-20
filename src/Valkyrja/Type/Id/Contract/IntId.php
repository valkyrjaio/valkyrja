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

namespace Valkyrja\Type\Id\Contract;

use Valkyrja\Type\Contract\Type;

/**
 * Interface IntID.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<int>
 */
interface IntId extends Type
{
    /**
     * @inheritDoc
     */
    public function asValue(): int;

    /**
     * @inheritDoc
     */
    public function asFlatValue(): int;
}
