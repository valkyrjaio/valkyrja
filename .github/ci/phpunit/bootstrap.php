<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

use Valkyrja\Application\Directory\Directory;

require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/../root-and-suggested/vendor/autoload.php';

require __DIR__ . '/../../../tests/bootstrap.php';

// The parallel path-coverage shards (see path-coverage-shards.sh) all execute in
// this one working copy, so they must not share the storage directory that
// Valkyrja\Tests\Abstract\TestCase::tearDown() wipes after every test. When
// VALKYRJA_TEST_STORAGE_PATH is set (to a path relative to the tests directory),
// point storage at that per-shard directory instead. It is unset everywhere else
// -- normal runs and CI, where each shard is its own checkout, are unaffected.
$shardStoragePath = getenv('VALKYRJA_TEST_STORAGE_PATH');

if (is_string($shardStoragePath) && $shardStoragePath !== '') {
    Directory::$storagePath = $shardStoragePath;
}
