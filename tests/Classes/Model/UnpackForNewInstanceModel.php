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

namespace Valkyrja\Tests\Classes\Model;

use Valkyrja\Model\Models\Model as AbstractModel;

/**
 * Model class to use to test UnpackForNewInstance model.
 *
 * @author Melech Mizrachi
 *
 * @property string $protected
 */
class UnpackForNewInstanceModel extends AbstractModel
{
    use PrivateProperty;

    public function __construct(
        public string $public,
        protected string $protected,
    ) {
    }
}
