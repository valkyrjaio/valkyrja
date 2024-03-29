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

namespace Valkyrja\Type\Types;

use Valkyrja\Type\Arr as Contract;

/**
 * Class Arr.
 *
 * @author Melech Mizrachi
 */
class Arr extends Type implements Contract
{
    public function __construct(array $subject)
    {
        parent::__construct($subject);
    }

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        return parent::get();
    }
}
