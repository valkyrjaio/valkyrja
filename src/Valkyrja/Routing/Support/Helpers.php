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

namespace Valkyrja\Routing\Support;

use Valkyrja\Path\Constants\PathSeparator;

/**
 * Class Helpers.
 *
 * @author Melech Mizrachi
 */
class Helpers
{
    /**
     * Get an entity param for a route.
     *
     * @param string      $param         The param name
     * @param string      $entity        The entity class
     * @param string|null $field         [optional] The field to query on
     * @param string[]    $relationships [optional] The relationships to include
     *
     * @return string
     */
    public static function getEntityParam(
        string $param,
        string $entity,
        string $field = null,
        string ...$relationships
    ): string {
        $classSeparator             = PathSeparator::ENTITY_CLASS;
        $fieldSeparator             = PathSeparator::ENTITY_FIELD;
        $withRelationshipsSeparator = PathSeparator::ENTITY_WITH_RELATIONSHIPS;

        $fieldAddition = $field ? "{$fieldSeparator}{$field}" : '';

        if (! empty($relationships)) {
            $relationshipsString = implode(PathSeparator::ENTITY_RELATIONSHIPS, $relationships);
            $fieldAddition       .= "{$withRelationshipsSeparator}{$relationshipsString}";
        }

        return "{$param}{$fieldAddition}{$classSeparator}{$entity}";
    }

    /**
     * Get an entity path for a route.
     *
     * @param string      $param         The param name
     * @param string      $entity        The entity class
     * @param string      $regex         The regex
     * @param string|null $field         [optional] The field to query on
     * @param string[]    $relationships [optional] The relationships to include
     *
     * @return string
     */
    public static function getEntityPath(
        string $param,
        string $entity,
        string $regex,
        string $field = null,
        string ...$relationships
    ): string {
        $entityRouteParam = static::getEntityParam($param, $entity, $field, ...$relationships);
        $regexSeparator   = PathSeparator::REGEX;

        return "/{{$entityRouteParam}{$regexSeparator}{$regex}}";
    }
}
