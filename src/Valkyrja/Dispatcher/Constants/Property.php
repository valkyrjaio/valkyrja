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

namespace Valkyrja\Dispatcher\Constants;

/**
 * Constant Property.
 *
 * @author Melech Mizrachi
 */
final class Property
{
    public const ID           = 'id';
    public const NAME         = 'name';
    public const CLASS_NAME   = 'class';
    public const PROPERTY     = 'property';
    public const METHOD       = 'method';
    public const STATIC       = 'static';
    public const FUNCTION     = 'function';
    public const CLOSURE      = 'closure';
    public const CONSTANT     = 'constant';
    public const VARIABLE     = 'variable';
    public const DEPENDENCIES = 'dependencies';
    public const ARGUMENTS    = 'arguments';
    public const MATCHES      = 'matches';
}
