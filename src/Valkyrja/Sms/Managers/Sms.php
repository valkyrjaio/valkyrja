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

namespace Valkyrja\Sms\Managers;

use Valkyrja\Sms\Config\Config;
use Valkyrja\Sms\Driver;
use Valkyrja\Sms\Factory;
use Valkyrja\Sms\Message;
use Valkyrja\Sms\Sms as Contract;
use Valkyrja\Support\Manager\Managers\MessageManager;

/**
 * Class SMS.
 *
 * @author Melech Mizrachi
 *
 * @property Factory $factory
 */
class Sms extends MessageManager implements Contract
{
    /**
     * Mail constructor.
     *
     * @param Factory      $factory The factory
     * @param Config|array $config  The config
     */
    public function __construct(Factory $factory, Config|array $config)
    {
        parent::__construct($factory, $config);

        $this->configurations = $config['messengers'];
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

    /**
     * @inheritDoc
     */
    public function send(Message $message): void
    {
        $this->use()->send($message);
    }
}