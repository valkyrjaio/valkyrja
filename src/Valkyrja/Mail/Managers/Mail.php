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

namespace Valkyrja\Mail\Managers;

use Valkyrja\Mail\Driver;
use Valkyrja\Mail\Loader;
use Valkyrja\Mail\Mail as Contract;
use Valkyrja\Mail\Message;
use Valkyrja\Support\Manager\Managers\MessageManager;

/**
 * Class Mail.
 *
 * @author Melech Mizrachi
 *
 * @property Loader $loader
 */
class Mail extends MessageManager implements Contract
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

        $this->configurations = $config['mailers'];
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
