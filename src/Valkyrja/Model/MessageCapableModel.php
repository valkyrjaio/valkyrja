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

namespace Valkyrja\Model;

use Valkyrja\Routing\Message;

/**
 * Interface MessageCapableModel.
 *
 * @author Melech Mizrachi
 */
interface MessageCapableModel extends Model
{
    /**
     * Create a model from a message.
     *
     * @param class-string<Message> $message The message class name
     * @param array                 $data    The data
     *
     * @return static
     */
    public static function fromMessage(string $message, array $data = []): static;

    /**
     * Get model as an array given a message.
     *
     * @param class-string<Message> $message       The message class name
     * @param string                ...$properties [optional] An array of properties to return
     *
     * @return array
     */
    public function asMessageArray(string $message, string ...$properties): array;
}
