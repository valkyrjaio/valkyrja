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

namespace Valkyrja\Tests\Classes\Model;

use Closure;
use RuntimeException;
use Valkyrja\Type\Model\CastableModel as AbstractModel;
use Valkyrja\Type\Model\Data\Cast;

use function json_encode;

use const JSON_THROW_ON_ERROR;

/**
 * Model class to use to test Castable model.
 *
 * @author Melech Mizrachi
 */
class EmptyCastableModel extends AbstractModel
{
    protected function internalSetProperties(array $properties, ?Closure $modifyValue = null): void
    {
        parent::internalSetProperties(
            $properties,
            static function (string $property, mixed $value, Cast|null $cast): mixed {
                throw new RuntimeException(json_encode([$property, $value, $cast], JSON_THROW_ON_ERROR));
            }
        );
    }
}
