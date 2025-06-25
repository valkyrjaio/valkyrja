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

namespace Valkyrja\Container\Constant;

/**
 * Class ConfigName.
 *
 * @author Melech Mizrachi
 */
final class ConfigName
{
    public const string ALIASES          = 'aliases';
    public const string SERVICES         = 'services';
    public const string CONTEXT_ALIASES  = 'contextAliases';
    public const string CONTEXT_SERVICES = 'contextServices';
    public const string PROVIDERS        = 'providers';
    public const string DEV_PROVIDERS    = 'devProviders';
    public const string USE_ATTRIBUTES   = 'useAttributes';
}
