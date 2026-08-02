<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Type\Model;

use Override;
use Valkyrja\Type\Model\Abstract\Model;

/**
 * Model class to test an invalid isset method.
 */
final class ModelInvalidIssetMethodFixture extends Model
{
    public string $test = 'test';

    public function issetTest(): string
    {
        return $this->test;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function internalIssetCallables(): array
    {
        return [
            'test' => [$this, 'issetTest'],
        ];
    }
}
