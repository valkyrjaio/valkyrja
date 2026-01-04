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

namespace Valkyrja\Tests\Classes\Orm;

use Valkyrja\Orm\Entity\Abstract\Entity;

/**
 * Model class to use to test abstract model.
 */
class EntityClass extends Entity
{
    /**
     * A property to test with.
     *
     * @var string|null
     */
    public string|null $property = null;

    /**
     * A property to test with using getter/setter.
     *
     * @var string|null
     */
    protected string|null $prop = null;

    /**
     * Get the prop.
     */
    public function getProp(): string|null
    {
        return $this->prop;
    }

    /**
     * Determine if the prop is set.
     */
    public function issetProp(): bool
    {
        return $this->prop !== null;
    }

    /**
     * Set the prop.
     *
     * @param string $prop The prop
     */
    public function setProp(string $prop): self
    {
        $this->prop = $prop;

        return $this;
    }
}
