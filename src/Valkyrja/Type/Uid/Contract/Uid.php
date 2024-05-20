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

namespace Valkyrja\Type\Uid\Contract;

use Valkyrja\Type\Contract\Type;

/**
 * Interface Uid.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<string>
 */
interface Uid extends Type
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
