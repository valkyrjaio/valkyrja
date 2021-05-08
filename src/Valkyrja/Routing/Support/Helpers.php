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
     * @param string      $paramName     The param name
     * @param string      $entityName    The entity class
     * @param string|null $fieldName     [optional] The field to query on
     * @param string[]    $relationships [optional] The relationships to include
     *
     * @return string
     */
    public static function getEntityRouteParam(
        string $paramName,
        string $entityName,
        string $fieldName = null,
        string ...$relationships
    ): string {
        $classSeparator             = PathSeparator::ENTITY_CLASS;
        $fieldSeparator             = PathSeparator::ENTITY_FIELD;
        $withRelationshipsSeparator = PathSeparator::ENTITY_WITH_RELATIONSHIPS;

        $fieldAddition = $fieldName ? "{$fieldSeparator}{$fieldName}" : '';

        if (! empty($relationships)) {
            $relationshipsString = implode(PathSeparator::ENTITY_RELATIONSHIPS, $relationships);

            $fieldAddition .= "{$withRelationshipsSeparator}{$relationshipsString}";
        }

        return "{$paramName}{$fieldAddition}{$classSeparator}{$entityName}";
    }

    /**
     * Get an entity path for a route.
     *
     * @param string      $paramName     The param name
     * @param string      $entityName    The entity class
     * @param string      $regex         The regex
     * @param string|null $fieldName     [optional] The field to query on
     * @param string[]    $relationships [optional] The relationships to include
     *
     * @return string
     */
    public static function getEntityPath(
        string $paramName,
        string $entityName,
        string $regex,
        string $fieldName = null,
        string ...$relationships
    ): string {
        return self::getEntityPathFromParam(
            static::getEntityRouteParam($paramName, $entityName, $fieldName, ...$relationships),
            $regex
        );
    }

    /**
     * Get an entity path for a route given an entity route param.
     *
     * @param string $entityRouteParam The entity route param
     * @param string $regex            The regex
     *
     * @return string
     */
    public static function getEntityPathFromParam(string $entityRouteParam, string $regex): string
    {
        $regexSeparator = PathSeparator::REGEX;

        return "/{{$entityRouteParam}{$regexSeparator}{$regex}}";
    }
}
