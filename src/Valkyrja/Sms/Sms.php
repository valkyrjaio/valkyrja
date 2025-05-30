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

namespace Valkyrja\Sms;

use Valkyrja\Manager\MessageManager as Manager;
use Valkyrja\Sms\Adapter\Contract\Adapter;
use Valkyrja\Sms\Contract\Sms as Contract;
use Valkyrja\Sms\Driver\Contract\Driver;
use Valkyrja\Sms\Factory\Contract\Factory;
use Valkyrja\Sms\Message\Contract\Message;

/**
 * Class SMS.
 *
 * @author Melech Mizrachi
 *
 * @property Factory $factory
 *
 * @extends Manager<Adapter, Driver, Factory, Message>
 */
class Sms extends Manager implements Contract
{
    /**
     * Mail constructor.
     *
     * @param Factory                     $factory The factory
     * @param Config|array<string, mixed> $config  The config
     */
    public function __construct(Factory $factory, Config|array $config)
    {
        parent::__construct($factory, $config);

        $this->configurations = $config['messengers'];
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
        /** @var Message $message */
        $message = parent::createMessage($name, $data);

        return $message;
    }

    /**
     * @inheritDoc
     */
    public function send(Message $message): void
    {
        $this->use()->send($message);
    }
}
