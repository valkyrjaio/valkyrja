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
use Valkyrja\Broadcast\Config\Config;
use Valkyrja\Broadcast\Driver;
use Valkyrja\Broadcast\Factory;
use Valkyrja\Broadcast\Message;
use Valkyrja\Manager\Managers\MessageManager as Manager;

/**
 * Class Broadcast.
 *
 * @author Melech Mizrachi
 *
 * @extends Manager<Driver, Factory, Message>
 *
 * @property Factory $factory
 */
class Broadcast extends Manager implements Contract
{
    /**
     * Broadcast constructor.
     *
     * @param Factory      $factory The factory
     * @param Config|array $config  The config
     */
    public function __construct(Factory $factory, Config|array $config)
    {
        parent::__construct($factory, $config);

        $this->configurations = $config['broadcasters'];
    }

    /**
     * @inheritDoc
     */
    public function use(string|null $name = null): Driver
    {
        /** @var Driver $driver */
        $driver = parent::use($name);

        return $driver;
    }

    /**
     * @inheritDoc
     */
    public function createMessage(string|null $name = null, array $data = []): Message
    {
        return parent::createMessage($name, $data);
    }
}
