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

use Arkitect\CLI\Config;
use Valkyrja\Arkitect\Rules;

return static function (Config $config): void {
    $srcDir   = __DIR__ . '/../../../src';
    $testsDir = __DIR__ . '/../../../tests';

    Rules::getRules(srcDir: $srcDir, testsDir: $testsDir)($config);
};
