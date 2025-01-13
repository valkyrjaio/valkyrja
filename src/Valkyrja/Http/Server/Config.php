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

use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Config\Config as Model;
use Valkyrja\Http\Server\Contract\RequestHandler;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envKeys = [
        'requestHandler' => EnvKey::HTTP_SERVER_REQUEST_HANDLER,
    ];

    /**
     * @var class-string<RequestHandler>
     */
    public string $requestHandler;
}
