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

/**
 * Trait ExposedModelTrait.
 *
 * @author Melech Mizrachi
 */
trait ExposedModelTrait
{
    /**
     * @inheritDoc
     */
    protected function __allProperties(bool $includeHidden = false): array
    {
        return $this->__allPropertiesIncludingHidden();
    }
}
