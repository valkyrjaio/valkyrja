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

namespace Valkyrja\Tests\Unit\Type\Model;

use Valkyrja\Tests\Classes\Model\UnpackForNewInstanceModelClass;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the UnpackForNewInstanceModelTest model.
 *
 * @author Melech Mizrachi
 */
class UnpackForNewInstanceModelTest extends TestCase
{
    public function testConstructUnpacking(): void
    {
        $public    = 'test';
        $protected = 'test2';

        $model = UnpackForNewInstanceModelClass::fromArray([
            'public'    => $public,
            'protected' => $protected,
        ]);

        self::assertSame($public, $model->public);
        self::assertSame($protected, $model->protected);
    }
}
