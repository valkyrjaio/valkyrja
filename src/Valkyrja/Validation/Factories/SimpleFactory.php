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

namespace Valkyrja\Validation\Factories;

use Valkyrja\Validation\Factory;
use Valkyrja\Validation\Rules\Base;

/**
 * Class SimpleFactory.
 *
 * @author Melech Mizrachi
 */
class SimpleFactory implements Factory
{
    /**
     * @inheritDoc
     */
    public function createRules(string $name): object
    {
        return new Base();
    }
}
