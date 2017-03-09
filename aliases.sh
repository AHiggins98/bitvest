#!/bin/sh

alias p='vendor/bin/phpunit --stop-on-failure'
alias pc='p --exclude-group db --coverage-html coverage'
