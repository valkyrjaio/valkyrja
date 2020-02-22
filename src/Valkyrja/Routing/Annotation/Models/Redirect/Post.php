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

use Valkyrja\Routing\Annotation\Models\Post as ParentClass;
use Valkyrja\Routing\Annotation\Route\Redirect\Post as Contract;

/**
 * Class Post.
 *
 * @author Melech Mizrachi
 */
class Post extends ParentClass implements Contract
{
    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->redirect = true;

        parent::__construct();
    }
}
