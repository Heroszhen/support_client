#! /bin/bash
#push to github

export PATH=/usr/local/bin:$PATH
export PATH=/Applications/MAMP/bin/php/php7.3.9/bin:$PATH

#curl -sS https://getcomposer.org/installer | php
mv composer.phar /Users/zhen/composer.phar
alias composer='/Users/zhen/composer.phar'