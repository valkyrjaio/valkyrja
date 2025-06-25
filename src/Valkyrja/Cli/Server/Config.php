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

namespace Valkyrja\Cli\Server;

use Valkyrja\Cli\Server\Constant\ConfigName;
use Valkyrja\Cli\Server\Constant\EnvName;
use Valkyrja\Cli\Server\Contract\InputHandler as InputHandlerContract;
use Valkyrja\Config\Config as ParentConfig;

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
        ConfigName::INPUT_HANDLER => EnvName::INPUT_HANDLER,
    ];

    /**
     * @param class-string<InputHandlerContract> $inputHandler
     */
    public function __construct(
        public string $inputHandler = InputHandler::class,
    ) {
    }
}
