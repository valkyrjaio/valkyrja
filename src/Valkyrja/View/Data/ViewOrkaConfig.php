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

namespace Valkyrja\View\Data;

use Valkyrja\View\Data\Contract\ViewOrkaConfigContract;
use Valkyrja\View\Orka\Constant\OrkaReplacementCollection;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;

class ViewOrkaConfig implements ViewOrkaConfigContract
{
    /**
     * @param non-empty-string                          $orkaPath             The directory that holds the templates
     * @param non-empty-string                          $orkaFileExtension    The file extension of a template
     * @param array<non-empty-string, non-empty-string> $orkaPaths            The extra named template directories
     * @param class-string<ReplacementContract>[]       $orkaCoreReplacements The core replacements
     * @param class-string<ReplacementContract>[]       $orkaReplacements     The extra replacements
     */
    public function __construct(
        public readonly string $orkaPath = '/resources/views',
        public readonly string $orkaFileExtension = '.orka.phtml',
        public readonly array $orkaPaths = [],
        public readonly array $orkaCoreReplacements = OrkaReplacementCollection::CORE,
        public readonly array $orkaReplacements = OrkaReplacementCollection::DEBUG,
    ) {
    }
}
