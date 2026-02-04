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

namespace Valkyrja\Type\Model\Trait;

use Override;

trait UnpackForNewInstance
{
    /**
     * @inheritDoc
     *
     * @param array<string, mixed> $properties The properties
     */
    #[Override]
    public static function fromArray(array $properties): static
    {
        $model = new static(...$properties);

        $model->internalSetProperties($properties);

        return $model;
    }
}
