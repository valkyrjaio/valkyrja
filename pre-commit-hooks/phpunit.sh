#!/usr/bin/env bash

color_red="\033[0;31m"
color_reset="\033[0m"

title="PHPUnit"

vendor_command="vendor/bin/phpunit"
system_command="phpunit"

exec_command=""

if [ -f "$vendor_command" ]; then
    exec_command=$vendor_command
elif hash $system_command 2> /dev/null; then
    exec_command=$system_command
else
    echo -e "${color_red}${title} is not found.${color_reset}"
    echo "${vendor_command} or ${system_command} is required."
    exit 1
fi

${exec_command} --no-coverage
XDEBUG_MODE=coverage ${exec_command} --coverage-text --stderr >/dev/null
