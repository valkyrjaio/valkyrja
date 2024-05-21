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

namespace Valkyrja\Tests\Unit\Annotations\Facades;

use Valkyrja\Annotation\Contract\Annotator as Contract;
use Valkyrja\Annotation\Facade\Annotator as Facade;
use Valkyrja\Tests\Unit\Facade\FacadeTestCase;

/**
 * Test the Annotator Facade service.
 *
 * @author Melech Mizrachi
 */
class AnnotatorTest extends FacadeTestCase
{
    /** @inheritDoc */
    protected static string $contract = Contract::class;
    /** @inheritDoc */
    protected static string $facade = Facade::class;

    /**
     * @inheritDoc
     */
    public static function methods(): array
    {
        return [
            ['getParser'],
            ['setParser'],
            ['classAnnotations'],
            ['classMembersAnnotations'],
            ['classAndMembersAnnotations'],
            ['propertyAnnotations'],
            ['propertiesAnnotations'],
            ['methodAnnotations'],
            ['methodsAnnotations'],
            ['functionAnnotations'],
        ];
    }
}
