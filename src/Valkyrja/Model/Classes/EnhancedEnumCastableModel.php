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

namespace Valkyrja\Model\Classes;

use Valkyrja\Model\Traits\EnhancedEnumCastableModelTrait;

/**
 * Class EnhancedEnumSupportModel.
 *
 * @author Melech Mizrachi
 */
abstract class EnhancedEnumCastableModel extends CastableModel
{
    use EnhancedEnumCastableModelTrait;
}
