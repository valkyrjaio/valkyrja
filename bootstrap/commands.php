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
        ->setPath('test:command< -is={is:alpha}*>[ -{optional}][ --repeating={cheese:alpha}*][ --name={name:alpha}[ --test={test:alpha}]]')
        ->setName('test:command')
        ->setDescription('Test example command')
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
