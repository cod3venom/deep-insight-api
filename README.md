# deep-insight-api


### [DOCS](https://drive.google.com/drive/folders/1sfA86ltTzbkX_eTbUedutfK3FNLWI_gx?usp=sharing)

## Setup

#### Flow

1. Create database:

```bash
php bin/console doctrine:database:create
```

2. Schema update:

```bash
php bin/console d:s:u --force
```

3. Migrations & Fixtures:

```bash
php bin/console doctrine:fixtures:load
```

4. Set database data style
```
ALTER DATABASE deep_insight SET datestyle TO 'ISO, DMY';
```
#### Database
- [SF Intro](https://symfony.com/doc/current/doctrine.html)

#### Front:Encore
- [SF Encore](https://symfony.com/doc/current/frontend/encore/installation.html)

#### Messenger S&QMH

Consuming Messages (Running the Worker)

```bash
php bin/console messenger:consume async

# use -vv to see details about what's happening
php bin/console messenger:consume async -vv
```


## Deploy 2 prod:
- [SF Deploy Flow](https://symfony.com/doc/current/deployment.html)
- [Messenger SV](https://symfony.com/doc/current/messenger.html#supervisor-configuration)
### Tech

Clear cache 4 prod mode:

```bash
php bin/console cache:clear --env=prod --no-debug
php bin/console cache:warmup --env=prod --no-debug
php bin/console assets:install --env=prod --no-debug --symlink
```

## Test
- [SF Testing](https://symfony.com/doc/current/testing.html)
