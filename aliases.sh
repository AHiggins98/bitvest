#!/bin/sh

alias p='vendor/bin/phpunit --stop-on-failure'
alias pc='p --exclude-group db --coverage-html coverage'
alias push='git push origin HEAD'
alias sync-master="git fetch upstream && git checkout master && git merge upstream/master && git push origin HEAD"
