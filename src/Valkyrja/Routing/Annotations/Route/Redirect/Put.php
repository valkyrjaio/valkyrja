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

use Valkyrja\Routing\Annotations\Route\Put as ParentClass;

/**
 * Class Put.
 *
 * @author Melech Mizrachi
 */
class Put extends ParentClass
{
    /**
     * Put constructor.
     */
    public function __construct()
    {
        $this->redirect = true;

        parent::__construct();
    }
}
