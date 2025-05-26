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

namespace Valkyrja\Broadcast\Config;

use Valkyrja\Broadcast\Constant\ConfigName;

/**
 * Class DefaultMessageConfiguration.
 *
 * @author Melech Mizrachi
 */
class DefaultMessageConfiguration extends MessageConfiguration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::FROM          => 'BROADCAST_DEFAULT_MESSAGE_FROM',
        ConfigName::MESSAGE_CLASS => 'BROADCAST_DEFAULT_MESSAGE_CLASS',
    ];

    public function __construct()
    {
        parent::__construct(
            from: 'Example',
        );
    }
}
