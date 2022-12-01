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

namespace Valkyrja\Support\Manager\Factories;

use Valkyrja\Support\Manager\Adapter;
use Valkyrja\Support\Manager\Driver;
use Valkyrja\Support\Manager\FactoryWithMessage as Contract;
use Valkyrja\Support\Manager\Message;

/**
 * Class SimpleFactoryWithMessage.
 *
 * @author   Melech Mizrachi
 * @implements Contract<Adapter, Driver, Message>
 */
class SimpleFactoryWithMessage extends SimpleFactory implements Contract
{
    /**
     * @inheritDoc
     */
    public function createMessage(string $name, array $config, array $data = []): Message
    {
        return new $name($data);
    }
}
