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

namespace Valkyrja\Support\Loader\Loaders;

use Valkyrja\Support\Loader\LoaderWithMessage;
use Valkyrja\Support\Manager\Message;

/**
 * Class SimpleLoaderWithMessage.
 *
 * @author Melech Mizrachi
 */
class SimpleLoaderWithMessage extends SimpleLoader implements LoaderWithMessage
{
    /**
     * @inheritDoc
     */
    public function createMessage(string $name, array $config, array $data = []): Message
    {
        return new $name($data);
    }
}