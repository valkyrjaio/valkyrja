<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
