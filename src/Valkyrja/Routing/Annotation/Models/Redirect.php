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

use Valkyrja\Routing\Annotation\Route\Redirect as Contract;

/**
 * Class Redirect.
 *
 * @author Melech Mizrachi
 */
class Redirect extends Route implements Contract
{
    /**
     * Redirect constructor.
     */
    public function __construct()
    {
        $this->redirect = true;
    }
}
