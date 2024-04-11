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
 * Interface UuidV6.
 *
 * @author Melech Mizrachi
 *
 * @extends Uuid<string>
 */
interface UuidV6 extends Uuid
{
    /**
     * @inheritDoc
     */
    public function asValue(): string;

    /**
     * @inheritDoc
     */
    public function asFlatValue(): string;
}
