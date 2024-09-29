# Laravel composer scaffold

[![CI](https://github.com/maks-oleksyuk/laravel-composer-scaffold/actions/workflows/ci.yml/badge.svg?branch=main)](//github.com/maks-oleksyuk/laravel-composer-scaffold/actions/workflows/ci.yml)
[![Laravel version](https://img.shields.io/badge/10%20%7C%2011-ff2d20?logo=laravel&label=Laravel&logoColor=fff)](//laravel.com 'Laravel')
[![Packagist Downloads](https://img.shields.io/packagist/dt/maks-oleksyuk/laravel-composer-scaffold?logo=packagist&label=Downloads&color=f28d1A&logoColor=fff)](//packagist.org/packages/maks-oleksyuk/laravel-composer-scaffold 'Packagist Downloads')

This project provides a Composer plugin that makes the Laravel Composer package work correctly in a Composer project.

It takes care of the following:

Placing the project files (such as `artisan`, `public/index.php`, ...) from the `laravel/laravel` to the desired
location at the root of the web page. Only single files can be placed using this plugin.

The purpose of scaffolding files is to allow Laravel sites to be fully managed by Composer.
This is done to allow a properly configured Composer template to create a file structure that exactly matches the
structure of the `laravel/laravel` files.

## Usage

Laravel Composer Scaffold is used by requiring `maks-oleksyuk/laravel-composer-scaffold` in your project, and providing
configuration settings in the extra section of your project's `composer.json` file.


Typically, the scaffold operations run automatically as needed, e.g. after `composer install`, so it is usually not
necessary to do anything different to scaffold a project once the configuration is set up in the project `composer.json`
file, as described below. To scaffold files directly, run:

```sh
composer laravel:scaffold
```

## Settings

By default, the plugin does not require any additional settings, so it will only work with the following files:
```text
artisan
.editorconfig
bootstrap/cache/.gitignore
public/.htaccess
public/index.php
public/favicon.ico
public/robots.txt
storage/app/.gitignore
storage/app/public/.gitignore
storage/app/private/.gitignore
storage/framework/cache/.gitignore
storage/framework/cache/data/.gitignore
storage/framework/sessions/.gitignore
storage/framework/testing/.gitignore
storage/framework/views/.gitignore
storage/framework/.gitignore
storage/logs/.gitignore
```

However, you can overwrite these settings by specifying the desired settings in the `composer.json` file:

```json
"extra": {
    "laravel-scaffold": {
        "files": [
            "artisan",
            ".editorconfig",
            "public/.htaccess",
            "public/index.php",
            "public/robots.txt"
        ]
    }
}
```
