# Miva Data Migration / Integration MVC Framework Skeleton

## About the Framework

This MVC web application framework skeleton was created to help aid in rapid development of Miva data migrations and integrations by establishing a common workflow.

The framework utilizes the [Slim](https://www.slimframework.com/) micro-framework to do most of the heavy lifting. Please note that prior knowledge of [Slim](https://www.slimframework.com/), as well as the packages listed below, are almost certainly a prerequisite in order to get up and running with the framework in a productive manner.

- [Illuminate Database](https://github.com/illuminate/database) - Database Layer.
- [Miva Remote Provision](https://github.com/pdeans/miva-provision) - Helper library for connecting and interacting with the Miva remote provisioning module.
- [PHP-DI integration with Slim](http://php-di.org/doc/frameworks/slim.html) - Dependency injection manager.
- [Slim Framework Twig View](https://github.com/slimphp/Twig-View) - Slim Framework view helper built on top of the Twig templating component.
- [PHP dotenv](https://github.com/vlucas/phpdotenv) - PHP version of the original Ruby dotenv; essentially loads environment variables from a `.env` file.

## Installation

This project uses [Composer](https://getcomposer.org/) to manage its dependencies. Installation steps for [Composer](https://getcomposer.org/) can be found [here](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

Issue the following command to create a new install:
`composer create-project pdeans/miva-migration`

## Getting Started

### Web Root/Public Directory

After installing the framework, you should configure your server's web root to point to the `httpdocs` directory if it is not setup already. The `index.php` file in this directory serves as the front controller for all HTTP requests entering your application.

### Configuration Files

All of the configuration files for the framework can be found in the `/config` directory. However, the configuration values are generally set in the `.env` file.

## License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).