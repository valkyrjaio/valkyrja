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

namespace Valkyrja\Test\Attribute;

use Attribute;

/**
 * Attribute DataProvider.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::IS_REPEATABLE)]
class DataProvider
{
    public array $data;

    public function __construct(
        mixed ...$data,
    ) {
        $this->data = $data;
    }
}
