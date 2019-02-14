ToDoList
========

Pour installer le projet :

- créer deux bases, symfony et symfony_test
- composer install
- npm install
- bin/console doctrine:schema:create
- bin/console doctrine:fixtures:load
- composer dump-autoload --optimize --no-dev --classmap-authoritative