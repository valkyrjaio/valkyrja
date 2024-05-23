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

namespace Valkyrja\Routing\Annotation\Route\Redirect\Permanent;

use Valkyrja\Http\Message\Constant\StatusCode;
use Valkyrja\Routing\Annotation\Route\Redirect\Any as ParentClass;

/**
 * Class Any.
 *
 * @author Melech Mizrachi
 */
class Any extends ParentClass
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
