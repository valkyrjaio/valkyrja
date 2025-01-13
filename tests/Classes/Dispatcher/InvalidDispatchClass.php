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

namespace Valkyrja\Tests\Classes\Dispatcher;

use Valkyrja\Dispatcher\Data\Dispatch;

/**
 * Invalid dispatch class to test with.
 *
 * @author Melech Mizrachi
 */
class InvalidDispatchClass extends Dispatch
{
    public static function fromArray(array $data): static
    {
        return new self();
    }
}
