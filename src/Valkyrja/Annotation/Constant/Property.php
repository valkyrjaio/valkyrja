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

namespace Valkyrja\Annotation\Constant;

/**
 * Constant Property.
 *
 * @author Melech Mizrachi
 */
final class Property
{
    public const string ID           = 'id';
    public const string NAME         = 'name';
    public const string CLASS_NAME   = 'class';
    public const string PROPERTY     = 'property';
    public const string METHOD       = 'method';
    public const string STATIC       = 'static';
    public const string FUNCTION     = 'function';
    public const string CLOSURE      = 'closure';
    public const string CONSTANT     = 'constant';
    public const string VARIABLE     = 'variable';
    public const string DEPENDENCIES = 'dependencies';
    public const string ARGUMENTS    = 'arguments';
    public const string MATCHES      = 'matches';
    public const string TYPE         = 'type';
    public const string PROPERTIES   = 'properties';
}
