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

namespace Valkyrja\Config\Models;

use ArrayAccess;
use Valkyrja\Model\Models\Model;

/**
 * Class ConfigModel.
 *
 * @author Melech Mizrachi
 */
class ConfigModel extends Model implements ArrayAccess
{
    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->{$offset};
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }
}
