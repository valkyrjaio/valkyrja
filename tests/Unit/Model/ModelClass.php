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

use Valkyrja\ORM\Model\Model;

/**
 * Model class to use to test abstract model.
 *
 * @author Melech Mizrachi
 */
class ModelClass extends \Valkyrja\ORM\Model\Model
{
    /**
     * A property to test with.
     *
     * @var string
     */
    protected $property;

    /**
     * A property to test with using getter/setter.
     *
     * @var string
     */
    protected $prop;

    /**
     * Get the prop.
     *
     * @return string
     */
    public function getProp():? string
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
     * @return \Valkyrja\Tests\Unit\Model\ModelClass
     */
    public function setProp(string $prop): ModelClass
    {
        $this->prop = $prop;

        return $this;
    }
}
