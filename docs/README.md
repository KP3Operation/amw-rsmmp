# Aviatmobileweb

## Overview

This repository contains the source code for the Avitmobileweb. This document provides important information about how to work with the code in this repository, and the workflow that this repository requires.

### Audience

The audience for this document includes KP3 developers and members who need to work with Aviatmobileweb code. This includes both developers who are new to the platform code base, and who may have only limited experience with Git-based version control procedures and workflows. This document targets that audience by providing specific details about Git.

All developers who work in this repository are expected to adhere to the practices and processes guidelines covered in this document.

[Back to Top](#aviatmobileweb)

### Revisions and Corrections

Think that this document could use some work? Make your suggestion in the [`Repository > Aviatmobileweb > Issues`](https://github.com/KP3Operation/AviatMobileWeb/issues).
[Back to Top](#aviatmobileweb)

---

## Contributing

Please read our contributing guidelines [here](CONTRIBUTING.md).

[Back to Top](#aviatmobileweb)

---

## Setup for Local Development

This section describes the process of setting up a local development environment.

[Back to Top](#aviatmobileweb)

### Prerequesites and One-Time Setup

#### PostgreSQL

Ensure you have installed [PostgreSQL](https://www.postgresql.org/) and create a brand new database (example: `aviatmobileweb`).

#### PHP

Ensure you have installed [PHP](https://www.php.net/), especially `PHP 8.2.x` and added it to your operating system path. You can verify if php have successfully installed by checking php version from your terminal.

```sh
$ php -v
```

and the output will be look like

```sh
PHP 8.2.9 (cli) (built: Aug  1 2023 12:41:16) (NTS Visual C++ 2019 x64)
Copyright (c) The PHP Group
Zend Engine v4.2.9, Copyright (c) Zend Technologies
    with Zend OPcache v8.2.9, Copyright (c), by Zend Technologies

```

#### Composer

Ensure you have installed [Composer](https://getcomposer.org/), and added it to your operating system path. You can verify if composer have successfully installed by checking from your terminal.

```sh
$ composer -v
```

and the output will be look like

```sh
   ______
  / ____/___  ____ ___  ____  ____  ________  _____
 / /   / __ \/ __ `__ \/ __ \/ __ \/ ___/ _ \/ ___/
/ /___/ /_/ / / / / / / /_/ / /_/ (__  )  __/ /
\____/\____/_/ /_/ /_/ .___/\____/____/\___/_/
                    /_/
Composer version 2.4.1 2022-08-20 11:44:50

Usage:
  command [options] [arguments]

...

```

#### NodeJS

Ensure you have installed [NodeJS](https://nodejs.org/), and added it to your operating system path. You can verify if NodeJS have successfully installed by checking from your terminal.

```sh
$ node -v
```

and the output will be look like

```sh
v18.8.0
```

also you need to verify if the `npm` is successfully installed

```sh
$ npm -v
```

and the output will be look like

```sh
8.18.0
```

#### Web Server

Ensure you have installed one of the following Web Server.

-   Apache
-   Nginx

You can also use the builtin development server that `Laravel` has, instead of using Apache or Nginx.
> Please use the laravel builtin development server only for development purpose, if you in production please use either Nginx or Apache.

[Back to Top](#aviatmobileweb)

### The Aviatmobileweb

This is a main project of aviatmobileweb that are written on top of Laravel framework.

1. Clone Aviatmobileweb project from repository to your local machine.
2. Navigate to the aviatmobileweb project from your terminal.
3. Run the command line installer.

```sh
$ php install
```
> You may need to re-verify the `.env` configs that has been generated.

---

You can also manually setup **Aviatmobileweb** by:

#### Backend Setup

Make sure [Prerequesites and One-Time Setup](#prerequesites-and-one-time-setup) has been setup properly.

Setup env variables file by duplicating and rename `.env.example` to `.env` if the `.env` file not exist yet. Please update `.env` value with your local development setup. Over all, you need to pay attention to the following `.env` vars:

```env
APP_NAME=Aviatmobileweb
APP_ENV=
APP_KEY=
APP_DEBUG=true
APP_URL=
APP_CALLING_CODE="+62"
APP_LOCALE=
APP_FALLBACK_LOCALE=
APP_OTP_WITH_QUEUE=false
APP_OTP_EXPIRED_IN=1800

...

DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

...

WATZAP_SEND_MESSAGE_URL=https://api.watzap.id/v1/send_message
WATZAP_VALIDATE_WHATSAPP_NUMBER_URL=https://api.watzap.id/v1/validate_number
WATZAP_API_KEY=fooBarBazz
WATZAP_NUMBER_KEY=fooBarBazz
WATZAP_VALIDATE_WHATSAPP_NUMBER=true

SIMRS_BASE_URL=http://103.111.202.214/live/WebService
SIMRS_ACCESS_KEY=MWApA
```

Install all composer packages by running command

```sh
$ composer install
```

Generate app key by running command

```sh
$ php artisan key:generate
```

Migrate all database migration by command

```sh
$ php artisan migrate
```

or if you want to drop all existing tables and start a fresh database you can run command

```sh
$ php artisan migrate:fresh
```

Seed initial data by running command

```sh
$ php artisan db:seed
```

You also needed to create a symlink for storage by running command

```sh
$ php artisan storage:link
```

You may need to clear all app caches and create a new one by running following command

```sh
$ php artisan cache:clear
$ php artisan route:clear
$ php artisan view:clear
$ php artisan config:clear
$ php artisan optimize
```


[Back to Top](#aviatmobileweb)

#### Frontend Setup

Make sure [Prerequesites and One-Time Setup](#prerequesites-and-one-time-setup) has been setup properly.

Install all npm packages by command

```sh
$ npm install
```

Build the frontend assets by command

```sh
$ npm run build
```

### Upgrade Existing Aviatmobileweb

If you already have existing copy of Aviatmobileweb, and already pull the latest changes from `main` branch, please run

```sh
$ composer install
$ npm install
$ npm run build
```

to ensure if any new composer or npm packages is installed correctly. You also may need to running the migration command

```sh
$ php artisan migrate
```

to ensure any new tables is migrated to the database.

[Back to Top](#aviatmobileweb)

## The Environtment `Variables`

Only the following environment attributes would require your attention out of the 67 environment variables that can be configured within this web application.

|                 Name                  |              Default Value               |                                                          Description                                                          |
|:-------------------------------------:|:----------------------------------------:|:-----------------------------------------------------------------------------------------------------------------------------:|
|              `APP_NAME`               |              AviatMobileWeb              | The web app name, please dot not include any white space(s), if you want to include it, please surround it with double quotes |
|               `APP_ENV`               |                  local                   |                                                      The app envitonment                                                      |
|               `APP_KEY`               |                                          |                                           The web application key, can not be blank                                           |
|              `APP_DEBUG`              |                   true                   |                                                    Show application debug                                                     |
|               `APP_URL`               |             http://localhost             |                                                The web application 'BASE' url                                                 |
|          `APP_CALLING_CODE`           |                  "+62"                   |                                                   The calling country code                                                    |
|             `APP_LOCALE`              |                    id                    |                                                 The application localization                                                  |
|         `APP_FALLBACK_LOCALE`         |                    en                    |                              The default value if the specific localization key does not exists                               |
|         `APP_OTP_WITH_QUEUE`          |                  false                   |                              Set it to `true` if you want to send the otp message by using queue                              |
|         `APP_OTP_EXPIRED_IN`          |                   1800                   |                                                The OTP code expired in seconds                                                |
|            `DB_CONNECTION`            |                  pgsql                   |                                                                                                                               |
|               `DB_HOST`               |                127.0.0.1                 |                                                                                                                               |
|               `DB_PORT`               |                   5432                   |                                                                                                                               |
|             `DB_DATABASE`             |              aviatmobileweb              |                                                                                                                               |
|             `DB_USERNAME`             |                   dev                    |                                                                                                                               |
|             `DB_PASSWORD`             |                   dev                    |                                                                                                                               |
|       `WATZAP_SEND_MESSAGE_URL`       |  https://api.watzap.id/v1/send_message   |                                              The `WatZap` send message endpoint                                               |
|   `WATZAP_VALIDATE_WHATSAPP_NUMBER`   |                  false                   |                       Set it to `true` if you want to validate whatsapp phone number before sending OTP                       |
| `WATZAP_VALIDATE_WHATSAPP_NUMBER_URL` | https://api.watzap.id/v1/validate_number |                                        The `WatZap` validate whatsapp number endpoint                                         |
|           `WATZAP_API_KEY`            |                FooBarBazz                |                                                     The `WatZap` API key                                                      |
|          `WATZAP_NUMBER_KEY`          |                FooBarBazz                |                                                    The `WatZap` number key                                                    |
|           `SIMRS_BASE_URL`            |  http://103.111.202.214/live/WebService  |                                                      The SIMRS Base URL                                                       |
|          `SIMRS_ACCESS_KEY`           |                  MWApA                   |                                                     The SIMRS access key                                                      |


[Back to Top](#aviatmobileweb)

## Minimum Server Specifications:
#### 1. Processor:
- Dual-core processor or higher (e.g., Intel Core i3 or equivalent)

#### 2.RAM (Memory):
- 4 GB RAM or more (Recommended: 8 GB RAM for better performance)

#### 3.Storage:
- SSD (Solid State Drive) with at least 20 GB of free space for the operating system and applications. Additional storage space for your application data and files.

#### 4.Network:
- Stable internet connection for package installations, updates, and accessing external APIs (Minimum: 10 Mbps recommended)

#### 5. Operating System:
- Linux-based OS (e.g., Ubuntu 20.04 LTS) is recommended for stability and performance. However, Laravel can also run on Windows and macOS.

#### 6. Web Server:
- Nginx 1.19 or Apache 2.4 (or later versions) with URL rewriting enabled.

#### 7. PHP:
- PHP 8.2 (or later) with necessary extensions installed (pdo, pdo_pgsql, openssl, mbstring, tokenizer, json, xml).

#### 8. Database:
- PostgreSQL 9.6 or later. Ensure the server has enough space for the database storage.

#### 10. Node.js:
- Node.js LTS (Long Term Support) version. You can check the LTS version on the Node.js [official website](https://nodejs.org/).


[Back to Top](#aviatmobileweb)

### Additional Considerations:
- Backup System: Implement a regular backup system to prevent data loss.
- Security: Configure firewalls, intrusion detection systems, and SSL certificates for secure communication.
- Monitoring: Set up server monitoring tools to track server performance, uptime, and resource usage.
- Load Balancing (For High Traffic): If you expect high traffic, consider implementing load balancing techniques.
- Caching: Utilize caching mechanisms (e.g., Redis) to optimize database and application performance.


Remember that these specifications are for small to medium-sized applications. For large-scale or high-traffic applications, you might need to invest in more powerful hardware and consider additional technologies like load balancers, distributed databases, and content delivery networks (CDNs) for optimal performance and scalability.

[Back to Top](#aviatmobileweb)
