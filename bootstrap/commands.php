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
        ->setPath('make:command -is={is:alpha}[ --name={name:alpha}[ --test={test:alpha}]]')
        ->setClosure(function ($is, $name = null, $test = null) {
            return [
                $is, $name, $test
            ];
        })
);
