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

namespace Valkyrja\Routing\Annotation\Models;

use Valkyrja\Http\Constants\RequestMethod;
use Valkyrja\Routing\Annotation\Route\Put as Contract;

/**
 * Class Put.
 *
 * @author Melech Mizrachi
 */
class Put extends Route implements Contract
{
    /**
     * Put constructor.
     */
    public function __construct()
    {
        $this->methods = [
            RequestMethod::PUT,
        ];
    }
}
