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
        ->setPath('make:command -is={is:alpha}[ -{optional}][ --repeating={cheese:alpha}*][ --name={name:alpha}[ --test={test:alpha}]]')
        ->setClosure(function ($is, $optional = null, $name = null, $test = null) {
            var_dump([
                $is,
                $optional,
                $name,
                $test,
            ]);

            return 1;
        })
);
