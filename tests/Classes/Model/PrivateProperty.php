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

namespace Valkyrja\Tests\Classes\Model;

/**
 * Trait PrivateProperty.
 *
 * @author Melech Mizrachi
 *
 * @property string $private
 */
trait PrivateProperty
{
    private string $private;

    /**
     * @return string
     */
    protected function getPrivate(): string
    {
        return $this->private;
    }

    /**
     * @return bool
     */
    protected function issetPrivate(): bool
    {
        return isset($this->private);
    }

    /**
     * @param string $private
     *
     * @return void
     */
    protected function setPrivate(string $private): void
    {
        $this->private = $private;
    }
}
