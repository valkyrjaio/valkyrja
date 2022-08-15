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

namespace Valkyrja\SMS\Managers;

use Valkyrja\SMS\Driver;
use Valkyrja\SMS\Loader;
use Valkyrja\SMS\Message;
use Valkyrja\SMS\SMS as Contract;
use Valkyrja\Support\Manager\Managers\MessageManager;

/**
 * Class SMS.
 *
 * @author Melech Mizrachi
 *
 * @property Loader $loader
 */
class SMS extends MessageManager implements Contract
{
    /**
     * Mail constructor.
     *
     * @param Loader $loader The loader
     * @param array  $config The config
     */
    public function __construct(Loader $loader, array $config)
    {
        parent::__construct($loader, $config);

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
