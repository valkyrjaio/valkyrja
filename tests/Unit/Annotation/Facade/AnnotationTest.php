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

namespace Valkyrja\Tests\Unit\Annotation\Facade;

use Valkyrja\Annotation\Contract\Annotations as Contract;
use Valkyrja\Annotation\Facade\Annotator as Facade;
use Valkyrja\Tests\Unit\Facade\FacadeTestCase;

/**
 * Test the Annotation Facade service.
 *
 * @author Melech Mizrachi
 */
class AnnotationTest extends FacadeTestCase
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
            ['forClass'],
            ['forClassMembers'],
            ['forClassAndMembers'],
            ['forClassProperty'],
            ['forClassProperties'],
            ['forClassMethod'],
            ['forClassMethods'],
            ['forFunction'],
        ];
    }
}
