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

namespace Valkyrja\Support\Loader;

use Valkyrja\Support\Manager\Message;

/**
 * Interface LoaderWithMessage.
 *
 * @author Melech Mizrachi
 */
interface LoaderWithMessage extends Loader
{
    /**
     * Create a new message.
     *
     * @param string $name   The message
     * @param array  $config The config
     * @param array  $data   [optional] The data
     *
     * @return Message
     */
    public function createMessage(string $name, array $config, array $data = []): Message;
}
