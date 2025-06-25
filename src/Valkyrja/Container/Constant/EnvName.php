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
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const string ALIASES          = 'CONTAINER_ALIASES';
    public const string SERVICES         = 'CONTAINER_SERVICES';
    public const string CONTEXT_ALIASES  = 'CONTAINER_CONTEXT_ALIASES';
    public const string CONTEXT_SERVICES = 'CONTAINER_CONTEXT_SERVICES';
    public const string PROVIDERS        = 'CONTAINER_PROVIDERS';
    public const string DEV_PROVIDERS    = 'CONTAINER_DEV_PROVIDERS';
    public const string USE_ATTRIBUTES   = 'CONTAINER_USE_ATTRIBUTES';
}
