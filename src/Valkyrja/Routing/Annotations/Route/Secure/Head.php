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

namespace Valkyrja\Routing\Annotations\Route\Secure;

use Valkyrja\Routing\Annotations\Route\Head as ParentClass;

/**
 * Class Head.
 *
 * @author Melech Mizrachi
 */
class Head extends ParentClass
{
    /**
     * Head constructor.
     */
    public function __construct()
    {
        $this->secure = true;

        parent::__construct();
    }
}
