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

namespace Valkyrja\Tests\Classes\Orm\Support;

use DateTime;
use Override;
use Valkyrja\Orm\Support\Helpers;

/**
 * Test helper class that simulates DateTime creation failure.
 */
class HelpersWithFailingDateTimeClass extends Helpers
{
    /**
     * @inheritDoc
     */
    #[Override]
    protected static function createDateTimeFromMicrotime(): DateTime|false
    {
        return false;
    }
}
