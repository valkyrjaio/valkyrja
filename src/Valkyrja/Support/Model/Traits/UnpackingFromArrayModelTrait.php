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

namespace Valkyrja\Support\Model\Traits;

/**
 * Trait UnpackingFromArrayModelTrait.
 *
 * @author Melech Mizrachi
 */
trait UnpackingFromArrayModelTrait
{
    /**
     * @inheritDoc
     */
    protected static function __getNew(array $properties): self
    {
        return new static(...$properties);
    }
}
