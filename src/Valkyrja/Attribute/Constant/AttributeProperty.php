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

namespace Valkyrja\Attribute\Constant;

/**
 * Constant AttributeProperty.
 *
 * @author Melech Mizrachi
 */
final class AttributeProperty
{
    public const NAME       = 'name';
    public const CLASS_NAME = 'class';
    public const PROPERTY   = 'property';
    public const METHOD     = 'method';
    public const FUNCTION   = 'function';
    public const CLOSURE    = 'closure';
    public const CONSTANT   = 'constant';
    public const ARGUMENTS  = 'arguments';
    public const STATIC     = 'static';
    public const OPTIONAL   = 'optional';
    public const DEFAULT    = 'default';
}
