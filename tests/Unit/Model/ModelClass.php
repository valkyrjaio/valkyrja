<?php

namespace Valkyrja\Tests\Unit\Model;

use Valkyrja\Model\Model;

/**
 * Model class to use to test abstract model.
 *
 * @author Melech Mizrachi
 */
class ModelClass extends Model
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
    public function getProp(): string
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
     * @return void
     */
    public function setProp(string $prop): void
    {
        $this->prop = $prop;
    }
}
