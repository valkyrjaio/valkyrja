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

namespace Valkyrja\Tests\Classes\Model;

use Valkyrja\Tests\Classes\Model\Trait\PrivatePropertyTrait;
use Valkyrja\Type\Model\Abstract\Model as AbstractModel;
use Valkyrja\Type\Model\Trait\UnpackForNewInstance;

/**
 * Model class to use to test UnpackForNewInstance model.
 *
 * @author Melech Mizrachi
 *
 * @property string $protected
 */
class UnpackForNewInstanceModelClass extends AbstractModel
{
    use PrivatePropertyTrait;
    use UnpackForNewInstance;

    public function __construct(
        public string $public,
        protected string $protected,
    ) {
    }
}
