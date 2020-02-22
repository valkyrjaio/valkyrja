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

namespace Valkyrja\Routing\Annotation\Models\Redirect;

use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Routing\Annotation\Models\Redirect as ParentClass;
use Valkyrja\Routing\Annotation\Route\Redirect\Permanent as Contract;

/**
 * Class Permanent.
 *
 * @author Melech Mizrachi
 */
class Permanent extends ParentClass implements Contract
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
