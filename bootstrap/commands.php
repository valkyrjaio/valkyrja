<?php

/*
 *-------------------------------------------------------------------------
 * Bind Application Console Commands
 *-------------------------------------------------------------------------
 *
 * TODO: ADD EXPLANATION
 *
 */

console()->addCommand(
    (new \Valkyrja\Console\Command())
        ->setPath('make:command {-is:+\d}[ {--name:+\d}[ {--test:+\d}]]')
);
