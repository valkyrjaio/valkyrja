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
