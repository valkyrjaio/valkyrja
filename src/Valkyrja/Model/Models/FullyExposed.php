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

namespace Valkyrja\Model\Models;

/**
 * Trait FullyExposed.
 *
 * @author Melech Mizrachi
 */
trait FullyExposed
{
    /**
     * @inheritDoc
     */
    protected function __allProperties(): array
    {
        return get_object_vars($this);
    }
}
