# Sorterings-ABC

## Installation

Copy `.env.dist` to `.env` and edit to match your setup.

```sh
composer install
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate --no-interaction
```

Build assets

```sh
yarn encore dev --watch
```
