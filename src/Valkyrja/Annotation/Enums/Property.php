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

namespace Valkyrja\Annotation\Enums;

use Valkyrja\Dispatcher\Enums\Property as DispatchProperty;
use Valkyrja\Support\Enum\Enum;

/**
 * Enum Property.
 *
 * @author Melech Mizrachi
 */
final class Property extends Enum
{
    public const ID           = DispatchProperty::ID;
    public const NAME         = DispatchProperty::NAME;
    public const CLASS_NAME   = DispatchProperty::CLASS_NAME;
    public const PROPERTY     = DispatchProperty::PROPERTY;
    public const METHOD       = DispatchProperty::METHOD;
    public const STATIC       = DispatchProperty::STATIC;
    public const FUNCTION     = DispatchProperty::FUNCTION;
    public const CLOSURE      = DispatchProperty::CLOSURE;
    public const CONSTANT     = DispatchProperty::CONSTANT;
    public const VARIABLE     = DispatchProperty::VARIABLE;
    public const DEPENDENCIES = DispatchProperty::DEPENDENCIES;
    public const ARGUMENTS    = DispatchProperty::ARGUMENTS;
    public const MATCHES      = DispatchProperty::MATCHES;
    public const TYPE         = 'type';
    public const PROPERTIES   = 'properties';
}
