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
use Valkyrja\Routing\Annotation\Route\Options as Contract;

/**
 * Class Options.
 *
 * @author Melech Mizrachi
 */
class Options extends Route implements Contract
{
    /**
     * Options constructor.
     */
    public function __construct()
    {
        $this->methods = [
            RequestMethod::OPTIONS,
        ];
    }
}
