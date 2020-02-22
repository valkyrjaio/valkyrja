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

use Valkyrja\Http\Enums\RequestMethod;
use Valkyrja\Routing\Annotation\Route\Any as Contract;

/**
 * Class Any.
 *
 * @author Melech Mizrachi
 */
class Any extends Route implements Contract
{
    /**
     * Any constructor.
     */
    public function __construct()
    {
        $this->methods = RequestMethod::ANY;
    }
}
