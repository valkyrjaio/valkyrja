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

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class MessageConfig extends Config
{
    /**
     * The default message.
     *
     * @var string
     */
    public string $defaultMessage;

    /**
     * The default message class.
     *
     * @var string
     */
    public string $message;

    /**
     * The messages.
     *
     * @var array<string, array<string, mixed>>
     */
    public array $messages;
}
