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

namespace Valkyrja\Routing\Annotation\Models;

use Valkyrja\Http\Enums\RequestMethod;
use Valkyrja\Routing\Annotation\Route\Post as Contract;

/**
 * Class Post.
 *
 * @author Melech Mizrachi
 */
class Post extends Route implements Contract
{
    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->methods = [
            RequestMethod::POST,
        ];
    }
}
