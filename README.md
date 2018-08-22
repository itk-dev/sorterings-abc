# Sorterings-ABC

## Installation

Copy `.env.dist` to `.env` and edit to match your setup.

```sh
composer install
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate --no-interaction
```

Create users

```sh
bin/console fos:user:create --super-admin super-admin super-admin@example.com super-admin-password
bin/console fos:user:create admin admin@example.com admin-password
bin/console fos:user:promote admin ROLE_ADMIN
```

Build assets

```sh
yarn encore dev --watch
```
