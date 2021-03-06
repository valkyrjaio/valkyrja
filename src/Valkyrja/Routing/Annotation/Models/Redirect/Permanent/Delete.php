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

namespace Valkyrja\Routing\Annotation\Models\Redirect\Permanent;

use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Routing\Annotation\Models\Redirect\Delete as ParentClass;
use Valkyrja\Routing\Annotation\Route\Redirect\Permanent\Delete as Contract;

/**
 * Class Delete.
 *
 * @author Melech Mizrachi
 */
class Delete extends ParentClass implements Contract
{
    /**
     * Delete constructor.
     */
    public function __construct()
    {
        $this->code = StatusCode::MOVED_PERMANENTLY;

        parent::__construct();
    }
}
