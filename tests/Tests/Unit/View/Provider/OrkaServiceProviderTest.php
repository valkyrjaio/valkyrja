<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\View\Provider;

use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\View\Orka\Constant\OrkaReplacementCollection;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Provider\ViewOrkaServiceProvider;

use function array_keys;
use function array_merge;

/**
 * Test the OrkaServiceProvider.
 */
final class OrkaServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ViewOrkaServiceProvider::class;

    /**
     * The provider binds what the default config selects — the core set, plus the debug set that
     * `orkaReplacements` defaults to.
     *
     * @return class-string<ReplacementContract>[]
     */
    private static function defaultReplacements(): array
    {
        return array_merge(OrkaReplacementCollection::CORE, OrkaReplacementCollection::DEBUG);
    }

    public function testExpectedPublishers(): void
    {
        self::assertSame(
            self::defaultReplacements(),
            array_keys(new ViewOrkaServiceProvider()->publishers())
        );
    }

    public function testPublishReplacementsBindsEveryDefaultReplacement(): void
    {
        $this->container->register(new ViewOrkaServiceProvider());

        foreach (self::defaultReplacements() as $replacement) {
            self::assertInstanceOf($replacement, $this->container->getSingleton($replacement));
            self::assertInstanceOf(ReplacementContract::class, $this->container->getSingleton($replacement));
        }
    }
}
