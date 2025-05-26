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

namespace Valkyrja\Event\Attribute;

use Attribute;
use Valkyrja\Event\Model\Listener as Model;

/**
 * Attribute Listener.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Listener extends Model
{
    /**
     * @param class-string $eventId
     */
    public function __construct(
        string $eventId,
        string|null $name = null,
    ) {
        $this->setEventId($eventId);
        $this->setName($name);
    }
}
