# Lonely Dirty Bomb Players #


## Installation ##

```
composer install
# should be automatically called afterwards:
npm install
bower install
# assetic also requires compass and sass, they can be installed from gemfile:
bundle install
```

Generating assets:
```
php app/console bazinga:js-translation:dump app/cache/js-translation
php app/console assetic:dump # can be omitted on dev
```

Creating database:
```
php app/console doctrine:database:create
php app/console doctrine:schema:create
php app/console doctrine:fixtures:load --append
```