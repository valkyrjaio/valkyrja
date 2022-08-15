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

namespace Valkyrja\Support\Manager\Managers;

use Valkyrja\Support\Loader\LoaderWithMessage;
use Valkyrja\Support\Manager\Message;
use Valkyrja\Support\Manager\MessageManager as Contract;

/**
 * Class MessageManager.
 *
 * @author Melech Mizrachi
 *
 * @property LoaderWithMessage $loader
 */
abstract class MessageManager extends Manager implements Contract
{
    /**
     * The default message class.
     *
     * @var string
     */
    protected string $defaultMessageClass;

    /**
     * The messages config.
     *
     * @var array[]
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
     * @param LoaderWithMessage $loader The loader
     * @param array             $config The config
     */
    public function __construct(LoaderWithMessage $loader, array $config)
    {
        parent::__construct($loader, $config);

        $this->defaultMessage      = $config['defaultMessage'];
        $this->defaultMessageClass = $config['message'];
        $this->messages            = $config['messages'];
    }

    /**
     * @inheritDoc
     */
    public function createMessage(string $name = null, array $data = []): Message
    {
        // The name of the message to use
        $name ??= $this->defaultMessage;
        // The message config
        $config = $this->messages[$name];
        // The message to use
        $message = $config['message'] ?? $this->defaultMessageClass;

        return $this->loader->createMessage($message, $config, $data);
    }
}
