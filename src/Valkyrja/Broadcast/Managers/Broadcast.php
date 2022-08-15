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

namespace Valkyrja\Broadcast\Managers;

use Valkyrja\Broadcast\Broadcast as Contract;
use Valkyrja\Broadcast\Driver;
use Valkyrja\Broadcast\Loader;
use Valkyrja\Broadcast\Message;
use Valkyrja\Support\Manager\Managers\MessageManager;

/**
 * Class Broadcast.
 *
 * @author Melech Mizrachi
 *
 * @property Loader $loader
 */
class Broadcast extends MessageManager implements Contract
{
    /**
     * Broadcast constructor.
     *
     * @param Loader $loader The loader
     * @param array  $config The config
     */
    public function __construct(Loader $loader, array $config)
    {
        parent::__construct($loader, $config);

        $this->configurations = $config['broadcasters'];
    }

    /**
     * @inheritDoc
     */
    public function use(string $name = null): Driver
    {
        return parent::use($name);
    }

    /**
     * @inheritDoc
     */
    public function createMessage(string $name = null, array $data = []): Message
    {
        return parent::createMessage($name, $data);
    }
}
