# Sorterings-ABC

## Installation

Set environment variables in your web server, e.g. for [Apache](https://httpd.apache.org/docs/2.4/mod/mod_env.html#setenv):

```
SetEnv APP_ENV prod
SetEnv APP_SECRET ChangeThis
SetEnv DATABASE_URL 'mysql://username:password@host:port/sorteringsabc'
SetEnv CORS_ALLOW_ORIGIN '^https?://'
```

```sh
composer install --no-dev --optimize-autoloader
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate --no-interaction
```

### Create users

```sh
bin/console fos:user:create --super-admin super-admin@example.com super-admin@example.com super-admin-password
bin/console fos:user:create admin@example.com admin@example.com admin-password
bin/console fos:user:promote admin@example.com ROLE_ADMIN
```

## Development

```sh
docker-compose exec phpfpm composer install
docker-compose exec phpfpm bin/console doctrine:migrations:migrate --no-interaction
docker-compose exec phpfpm bin/console fos:user:create --super-admin super-admin@example.com super-admin@example.com super-admin-password
```

### Building assets


Install yarn packages:

```sh
docker run --volume ${PWD}:/app itkdev/yarn:latest install
docker run --volume ${PWD}:/app itkdev/yarn:latest encore dev
```

Build for production:

```sh
docker run --volume ${PWD}:/app itkdev/yarn:latest encore production
```

`git add` and `commit` changes in `public/build`.

During development:

```sh
docker run --volume ${PWD}:/app itkdev/yarn:latest --interactive --tty encore dev --watch
```

### Translations

Update translations by running

```sh
bin/console translation:update da --force
```
