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

namespace Valkyrja\Routing\Annotation\Models\Secure;

use Valkyrja\Routing\Annotation\Models\Get as ParentClass;
use Valkyrja\Routing\Annotation\Route\Secure\Get as Contract;

/**
 * Class Get.
 *
 * @author Melech Mizrachi
 */
class Get extends ParentClass implements Contract
{
    /**
     * Get constructor.
     */
    public function __construct()
    {
        $this->secure = true;

        parent::__construct();
    }
}
