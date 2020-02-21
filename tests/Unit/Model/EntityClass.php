<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Model;

use Valkyrja\ORM\Entities\EntityTrait;
use Valkyrja\ORM\Entity;

/**
 * Model class to use to test abstract model.
 *
 * @author Melech Mizrachi
 */
class EntityClass implements Entity
{
    use EntityTrait;

    /**
     * A property to test with.
     *
     * @var string|null
     */
    protected ?string $property = null;

    /**
     * A property to test with using getter/setter.
     *
     * @var string|null
     */
    protected ?string $prop = null;

    /**
     * Get the prop.
     *
     * @return string
     */
    public function getProp(): ?string
    {
        return $this->prop;
    }

    /**
     * Determine if the prop is set.
     *
     * @return bool
     */
    public function issetProp(): bool
    {
        return null !== $this->prop;
    }

    /**
     * Set the prop.
     *
     * @param string $prop The prop
     *
     * @return EntityClass
     */
    public function setProp(string $prop): EntityClass
    {
        $this->prop = $prop;

        return $this;
    }
}
