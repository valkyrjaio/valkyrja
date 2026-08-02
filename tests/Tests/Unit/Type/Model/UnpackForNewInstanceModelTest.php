<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Model;

use Valkyrja\Tests\Fixtures\Type\Model\UnpackForNewInstanceModelFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the UnpackForNewInstanceModelTest model.
 */
final class UnpackForNewInstanceModelTest extends TestCase
{
    public function testConstructUnpacking(): void
    {
        $public    = 'test';
        $protected = 'test2';

        $model = UnpackForNewInstanceModelFixture::fromArray([
            'public'    => $public,
            'protected' => $protected,
        ]);

        self::assertSame($public, $model->public);
        self::assertSame($protected, $model->protected);
    }
}
