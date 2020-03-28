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

namespace Valkyrja\Container\Enums;

use Valkyrja\Enum\Enums\Enum;

/**
 * Enum Config.
 *
 * @author Melech Mizrachi
 */
final class Config extends Enum
{
    public const PROVIDERS = [
        Provider::API,
        Provider::ANNOTATOR,
        Provider::AUTH,
        Provider::CACHE,
        Provider::CLIENT,
        Provider::CONSOLE,
        Provider::CONSOLE_KERNEL,
        Provider::INPUT,
        Provider::OUTPUT,
        Provider::COMMAND_ANNOTATOR,
        Provider::CONTAINER_ANNOTATOR,
        Provider::CRYPT,
        Provider::CRYPT_ENCRYPTER,
        Provider::CRYPT_DECRYPTER,
        Provider::LISTENER_ANNOTATOR,
        Provider::FILESYSTEM,
        Provider::KERNEL,
        Provider::REQUEST,
        Provider::RESPONSE,
        Provider::JSON_RESPONSE,
        Provider::REDIRECT_RESPONSE,
        Provider::RESPONSE_BUILDER,
        Provider::LOGGER,
        Provider::MAIL,
        Provider::ENTITY_MANAGER,
        Provider::PATH_GENERATOR,
        Provider::PATH_PARSER,
        Provider::REFLECTOR,
        Provider::ROUTER,
        Provider::ROUTE_ANNOTATOR,
        Provider::SESSION,
        Provider::VIEW,
    ];

    public const DEV_PROVIDERS = [];
}
