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

namespace Valkyrja\Routing\Annotations\Route\Redirect\Permanent;

use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Routing\Annotations\Route\Redirect\Get as ParentClass;

/**
 * Class Get.
 *
 * @author Melech Mizrachi
 */
class Get extends ParentClass
{
    /**
     * Get constructor.
     */
    public function __construct()
    {
        $this->code = StatusCode::MOVED_PERMANENTLY;

        parent::__construct();
    }
}
