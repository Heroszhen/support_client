#! /bin/bash
#push to github

git add -A 
git commit -m "maj"
git push origin master

php bin/console cache:clear
php bin/console cache:clear --env=prod
php bin/console cache:clear --env=dev
