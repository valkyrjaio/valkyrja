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

namespace Valkyrja\Routing\Annotation\Models;

use Valkyrja\Routing\Annotation\Route\Secure as Contract;

/**
 * Class Secure.
 *
 * @author Melech Mizrachi
 */
class Secure extends Route implements Contract
{
    /**
     * Secure constructor.
     */
    public function __construct()
    {
        $this->secure = true;
    }
}
