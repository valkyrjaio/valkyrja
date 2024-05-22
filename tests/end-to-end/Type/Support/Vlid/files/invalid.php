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

require_once __DIR__ . '/../../../../../bootstrap.php';

use Valkyrja\Test\Assert\Assert;
use Valkyrja\Test\Output\Outputters\EchoOutput;
use Valkyrja\Test\Tester\Test;
use Valkyrja\Type\Vlid\Exception\InvalidVlidException;
use Valkyrja\Type\Vlid\Support\Vlid;

(new Test(__FILE__, new EchoOutput()))->run(
    static function (Assert $assert): void {
        $vlid = 'test';

        $assert->exceptions->className(InvalidVlidException::class);
        $assert->exceptions->message("Invalid VLID $vlid provided.");

        Vlid::validate($vlid);
    }
);
