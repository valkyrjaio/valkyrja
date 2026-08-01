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

namespace Valkyrja\Tests\Fixtures\Crypt\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Crypt\Data\Contract\CryptConfigContract;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Crypt\Manager\NullCrypt;

final class CryptConfigFixture extends Config implements CryptConfigContract
{
    /**
     * @param class-string<CryptContract> $defaultCrypt
     */
    public function __construct(
        public string $defaultCrypt = NullCrypt::class,
    ) {
        parent::__construct();
    }
}
