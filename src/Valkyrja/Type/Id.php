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

namespace Valkyrja\Type;

/**
 * Interface Id.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<string|int>
 */
interface Id extends Type
{
    /**
     * @inheritDoc
     */
    public function asValue(): string|int;

    /**
     * @inheritDoc
     */
    public function asFlatValue(): string|int;
}
