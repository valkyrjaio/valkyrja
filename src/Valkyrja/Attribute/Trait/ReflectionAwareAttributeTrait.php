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

namespace Valkyrja\Attribute\Trait;

use Reflector;

trait ReflectionAwareAttributeTrait
{
    protected Reflector|null $reflection = null;

    /**
     * Get the reflection.
     *
     * @return Reflector|null
     */
    public function getReflection(): Reflector|null
    {
        return $this->reflection;
    }

    /**
     * Set the reflection.
     *
     * @param Reflector $reflection The reflection
     */
    public function setReflection(Reflector $reflection): void
    {
        $this->reflection = $reflection;
    }
}
