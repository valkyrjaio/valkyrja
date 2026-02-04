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

namespace Valkyrja\Tests\Classes\Model\Trait;

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
