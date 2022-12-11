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

use Valkyrja\Model\Models\Model as AbstractModel;

/**
 * Model class to use to test abstract model.
 *
 * @author Melech Mizrachi
 */
class Model extends AbstractModel
{
    /**
     * A property to test with.
     *
     * @var string|null
     */
    public ?string $property = null;

    /**
     * A property to test with using getter/setter.
     *
     * @var string|null
     */
    protected ?string $prop = null;

    /**
     * Get the prop.
     *
     * @return string|null
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
     * @return static
     */
    public function setProp(string $prop): self
    {
        $this->prop = $prop;

        return $this;
    }
}
