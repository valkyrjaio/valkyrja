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

use Valkyrja\Container\Container;
use Valkyrja\Mail\Mail as Contract;
use Valkyrja\Mail\Message;

/**
 * Class Mail.
 *
 * @author Melech Mizrachi
 */
class Mail implements Contract
{
    /**
     * The message clients.
     *
     * @var Message[]
     */
    protected static array $messages = [];

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The default message to use.
     *
     * @var string
     */
    protected string $defaultMessage;

    /**
     * SMS constructor.
     *
     * @param Container $container
     * @param array     $config The SMS config
     */
    public function __construct(Container $container, array $config)
    {
        $this->config         = $config;
        $this->container      = $container;
        $this->defaultMessage = $config['message'];
    }

    /**
     * Create a new message.
     *
     * @param string|null $name [optional] The name of the message
     *
     * @return Message
     */
    public function createMessage(string $name = null): Message
    {
        $name ??= $this->defaultMessage;

        if (isset(self::$messages[$name])) {
            return self::$messages[$name]->create();
        }

        $messageClass          = $this->config['messages'][$name];
        self::$messages[$name] = $this->container->getSingleton($messageClass);

        return self::$messages[$name]->create();
    }
}
