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

namespace Valkyrja\Model\Traits;

use Valkyrja\Support\Type\Obj;

/**
 * Trait ExposedProtectedModelTrait.
 *
 * @author Melech Mizrachi
 */
trait ExposedProtectedModelTrait
{
    /**
     * @inheritDoc
     */
    protected function __allProperties(bool $includeHidden = false): array
    {
        return $includeHidden
            ? $this->__allPropertiesIncludingHidden()
            : array_merge(Obj::getAllProperties($this, includePrivate: false), $this->__exposed);
    }
}