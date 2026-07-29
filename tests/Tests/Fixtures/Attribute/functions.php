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

namespace Valkyrja\Tests\Fixtures\Attribute;

/**
 * An attributed function for the collector's function and parameter lookups.
 *
 * It lives at the top level rather than inside the test method that uses it, so the
 * collector has a real function to reflect over.
 */
#[AttributeFixture(1)]
#[AttributeFixture(2)]
#[AttributeClassChildFixture(3, 'three')]
function attributedFixtureFunction(
    #[AttributeFixture(1)]
    #[AttributeFixture(2)]
    #[AttributeClassChildFixture(3, 'three')]
    string $param
): void {
}
