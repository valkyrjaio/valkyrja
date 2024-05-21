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

namespace Valkyrja\Manager;

use Valkyrja\Manager\Config\MessageConfig;
use Valkyrja\Manager\Contract\MessageManager as Contract;
use Valkyrja\Manager\Drivers\Contract\Driver;
use Valkyrja\Manager\Factories\Contract\MessageFactory as Factory;
use Valkyrja\Manager\Message\Contract\Message;

/**
 * Class MessageManager.
 *
 * @author   Melech Mizrachi
 *
 * @template Driver
 * @template Factory
 * @template Message
 *
 * @extends Manager<Driver, Factory>
 *
 * @implements Contract<Driver, Factory, Message>
 *
 * @property Factory $factory
 */
abstract class MessageManager extends Manager implements Contract
{
    /**
     * The default message class.
     *
     * @var class-string<Message>
     */
    protected string $defaultMessageClass;

    /**
     * The messages config.
     *
     * @var array<string, array>
     */
    protected array $messages;

    /**
     * The default message.
     *
     * @var string
     */
    protected string $defaultMessage;

    /**
     * MessageManager constructor.
     *
     * @param Factory             $factory The factory
     * @param MessageConfig|array $config  The config
     */
    public function __construct(Factory $factory, MessageConfig|array $config)
    {
        parent::__construct($factory, $config);

        $this->defaultMessage      = $config['defaultMessage'];
        $this->defaultMessageClass = $config['message'];
        $this->messages            = $config['messages'];
    }

    /**
     * @inheritDoc
     */
    public function createMessage(string|null $name = null, array $data = []): Message
    {
        // The name of the message to use
        $name ??= $this->defaultMessage;
        // The message config
        $config = $this->messages[$name];
        // The message to use
        $message = $config['message'] ?? $this->defaultMessageClass;

        return $this->factory->createMessage($message, $config, $data);
    }
}
