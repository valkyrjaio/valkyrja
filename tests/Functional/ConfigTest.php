<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Functional;

use Valkyrja\Tests\Config;

/**
 * Class ConfigTest
 *
 * @package Valkyrja\Tests\Functional
 */
class ConfigTest extends Config
{
    /**
     * ConfigTest constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->app->debug = true;
    }
}
