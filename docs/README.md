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

### Composer
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

### NodeJS
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
- Apache
- Nginx

You can also use the builtin development server that `Laravel` has, instead of using Apache or Nginx.

[Back to Top](#aviatmobileweb)

### The Aviatmobileweb

This is a main project of aviatmobileweb that are written on top of Laravel framework.

1. Clone Aviatmobileweb project from repository to your local machine.
2. Navigate to the aviatmobileweb project from your terminal.
3. Run the command line installer.
```sh
$ php install
```

You can also manually setup **Aviatmobileweb** by:

#### Backend Setup
Make sure [Prerequesites and One-Time Setup](#prerequesites-and-one-time-setup) has been setup properly.

Setup env variables file by duplicate and rename `.env.example` to `.env` if the `.env` file not exist yet. Please update `.env` value with your local development setup. Over all, you need to pay attention to the following `.env` vars:

```env
APP_NAME=Aviatmobileweb
APP_ENV=
APP_KEY=
APP_DEBUG=true
APP_URL=
APP_CALLING_CODE="+62"
APP_LOCALE=
APP_FALLBACK_LOCALE=

...

DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

...

WATZAP_SEND_MESSAGE_URL=
WATZAP_API_KEY=
WATZAP_NUMBER_KEY=
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
$ php artisan optimize
```

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
