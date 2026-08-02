<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Type\Model\Trait;

/**
 * Trait PrivateProperty.
 *
 * @property string $private
 */
trait PrivatePropertyTrait
{
    private string $private;

    /**
     * @inheritDoc
     */
    protected function internalGetCallables(): array
    {
        return [
            'private' => [$this, 'getPrivate'],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function internalSetCallables(): array
    {
        return [
            'private' => [$this, 'setPrivate'],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function internalIssetCallables(): array
    {
        return [
            'private' => [$this, 'issetPrivate'],
        ];
    }

    protected function getPrivate(): string
    {
        return $this->private;
    }

    protected function issetPrivate(): bool
    {
        return isset($this->private);
    }

    protected function setPrivate(string $private): void
    {
        $this->private = $private;
    }
}
