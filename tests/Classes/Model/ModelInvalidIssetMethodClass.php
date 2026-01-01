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

namespace Valkyrja\Tests\Classes\Model;

use Valkyrja\Type\Model\Abstract\Model as AbstractModel;

/**
 * Model class to test an invalid isset method.
 */
class ModelInvalidIssetMethodClass extends AbstractModel
{
    public string $test = 'test';

    public function issetTest(): string
    {
        return $this->test;
    }
}
