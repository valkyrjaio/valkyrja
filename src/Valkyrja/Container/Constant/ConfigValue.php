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

use Valkyrja\Container\Support\Provider as ContainerProvider;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    /** @var array<array-key, class-string<ContainerProvider>> */
    public const array PROVIDERS = [
        Provider::DISPATCHER,
        Provider::EVENT,
        Provider::ANNOTATION,
        Provider::API,
        Provider::ATTRIBUTES,
        Provider::AUTH,
        Provider::BROADCAST,
        Provider::CACHE,
        Provider::CLI_MESSAGE,
        Provider::CLI_MIDDLEWARE,
        Provider::CLI_ROUTING,
        Provider::CLI_SERVER,
        Provider::CLIENT,
        Provider::CONTAINER,
        Provider::CRYPT,
        Provider::FILESYSTEM,
        Provider::HTTP_MESSAGE,
        Provider::HTTP_SERVER,
        Provider::HTTP_MIDDLEWARE,
        Provider::JWT,
        Provider::LOG,
        Provider::MAIL,
        Provider::NOTIFICATION,
        Provider::ORM,
        Provider::PATH,
        Provider::REFLECTION,
        Provider::HTTP_ROUTING,
        Provider::SESSION,
        Provider::SMS,
        Provider::VIEW,
    ];
    /** @var array<array-key, class-string<ContainerProvider>> */
    public const array DEV_PROVIDERS = [];
}
