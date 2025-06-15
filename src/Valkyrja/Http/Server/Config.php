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

namespace Valkyrja\Http\Server;

use Valkyrja\Config\Config as ParentConfig;
use Valkyrja\Http\Server\Constant\ConfigName;
use Valkyrja\Http\Server\Constant\EnvName;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::REQUEST_HANDLER => EnvName::REQUEST_HANDLER,
    ];

    public function __construct(
        public string $requestHandler = RequestHandler::class,
    ) {
    }
}
