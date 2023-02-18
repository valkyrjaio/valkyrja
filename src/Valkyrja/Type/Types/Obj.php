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

namespace Valkyrja\Type\Types;

use Valkyrja\Type\Obj as Contract;

/**
 * Class Obj.
 *
 * @author Melech Mizrachi
 */
class Obj extends Type implements Contract
{
    public function __construct(object $subject)
    {
        parent::__construct($subject);
    }

    /**
     * @inheritDoc
     */
    public function get(): object
    {
        return parent::get();
    }
}
