<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Routing\Annotations;

use Valkyrja\Contracts\Annotations\AnnotationsParser;

/**
 * Interface RouteParser
 *
 * @package Valkyrja\Contracts\Routing\Annotations
 *
 * @author  Melech Mizrachi
 */
interface RouteParser extends AnnotationsParser
{
    /**
     * Route regex.
     *
     * @constant string
     */
    public const ROUTE_REGEX = '@Route' . self::CLASS_REGEX;
}
