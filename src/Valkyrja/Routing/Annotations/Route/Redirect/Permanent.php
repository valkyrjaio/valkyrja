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

namespace Valkyrja\Routing\Annotations\Route\Redirect;

use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Routing\Annotations\Route\Redirect as ParentClass;

/**
 * Class Permanent.
 *
 * @author Melech Mizrachi
 */
class Permanent extends ParentClass
{
    /**
     * Any constructor.
     */
    public function __construct()
    {
        $this->code = StatusCode::MOVED_PERMANENTLY;

        parent::__construct();
    }
}
