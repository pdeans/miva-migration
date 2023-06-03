> This package is **abandoned** and no longer maintained.

## Miva Data Migration / Integration MVC Framework Skeleton

### About the Framework

This PHP based MVC web application framework skeleton was created to help aid in rapid development of Miva data migrations and integrations by establishing a common workflow.

The framework utilizes the elegant [Laravel](https://laravel.com/) framework to do most of the heavy lifting. Please note that prior knowledge of [Laravel](https://laravel.com/), as well as the packages listed below, are almost certainly a prerequisite in order to get up and running with the framework in a productive manner.

- [Miva JSON Api PHP Library](https://github.com/pdeans/miva-api) - Helper library for interacting with the Miva JSON API.
- [PHP dotenv](https://github.com/vlucas/phpdotenv) - PHP version of the original Ruby dotenv; essentially loads environment variables from a `.env` file.

### Installation

This project uses [Composer](https://getcomposer.org/) to manage its dependencies. Installation steps for [Composer](https://getcomposer.org/) can be found [here](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

Issue the following command to create a new install:

```
$ composer create-project pdeans/miva-migration
```

### Getting Started

#### Web Root/Public Directory

After installing the framework, you should configure your server's web root to point to the `/public` directory if it is not setup already. The `index.php` file in this directory serves as the front controller for all HTTP requests entering the application.

#### Configuration Files

All of the configuration files for the framework can be found in the `/config` directory. However, the configuration values are generally set in the `.env` file.

### Versions

The following lists the main framework components included with each version, as well as the minimum PHP version required.

**Version 3:**

- Laravel 5.*
- Miva JSON API Library
- PHP 7.1.3+

**Version 2:**

- Laravel 5.5
- Miva Remote Provision Library
- PHP 7.0.0+

**Version 1:**

- Slim 3
- Miva Remote Provision Library
- PHP 5.6.4+

### License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
