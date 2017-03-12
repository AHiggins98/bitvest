#!/bin/sh

alias p='vendor/bin/phpunit --stop-on-failure'
alias pc='p --exclude-group db --coverage-html coverage --coverage-clover ./clover.xml' 
alias cov-check='php coverage-checker.php clover.xml 100';
alias git-push='git push origin HEAD'
alias sync-master="git fetch upstream && git checkout master && git merge upstream/master && git push origin HEAD"
alias pcf='vendor/bin/php-cs-fixer'
alias cs-fix='pcf fix app && pcf fix tests && pcf fix public'
