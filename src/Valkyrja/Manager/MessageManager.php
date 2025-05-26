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

use Valkyrja\Manager\Adapter\Contract\Adapter;
use Valkyrja\Manager\Contract\MessageManager as Contract;
use Valkyrja\Manager\Driver\Contract\Driver;
use Valkyrja\Manager\Factory\Contract\MessageFactory as Factory;
use Valkyrja\Manager\Message\Contract\Message;

/**
 * Class MessageManager.
 *
 * @author   Melech Mizrachi
 *
 * @template Adapter of Adapter
 * @template Driver of Driver
 * @template Factory of Factory
 * @template Message of Message
 *
 * @extends Manager<Adapter, Driver, Factory>
 *
 * @implements Contract<Adapter, Driver, Factory, Message>
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
     * @var array<string, array<string, mixed>>
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
     * @param Factory                            $factory The factory
     * @param MessageConfig|array<string, mixed> $config  The config
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
        /** @var class-string<Message> $class */
        $class = $config['message'] ?? $this->defaultMessageClass;

        /** @var Message $message */
        $message = $this->factory->createMessage($class, $config, $data);

        return $message;
    }
}
