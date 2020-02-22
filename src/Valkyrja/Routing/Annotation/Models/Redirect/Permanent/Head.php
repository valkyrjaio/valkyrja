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

namespace Valkyrja\Routing\Annotation\Models\Redirect\Permanent;

use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Routing\Annotation\Models\Redirect\Head as ParentClass;
use Valkyrja\Routing\Annotation\Route\Redirect\Permanent\Head as Contract;

/**
 * Class Head.
 *
 * @author Melech Mizrachi
 */
class Head extends ParentClass implements Contract
{
    /**
     * Head constructor.
     */
    public function __construct()
    {
        $this->code = StatusCode::MOVED_PERMANENTLY;

        parent::__construct();
    }
}
