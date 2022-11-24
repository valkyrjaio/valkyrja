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

namespace Valkyrja\Support\Model;

use ReflectionAttribute;

/**
 * Interface AttributedModel.
 *
 * @author Melech Mizrachi
 */
interface AttributedModel extends Model
{
    /**
     * Get the model's attributes.
     *
     * @param string|null $name [optional] The attribute name to filter by
     *
     * @return ReflectionAttribute[]
     */
    public static function getAttributes(string $name = null): array;
}
