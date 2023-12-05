# Laravel RESAPI Generator With Requests & Resources

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bhavingajjar/laravel-api-generator.svg?style=flat-square)](https://packagist.org/packages/bhavingajjar/laravel-api-generator)
[![Build Status](https://img.shields.io/travis/bhavingajjar/laravel-api-generator/master.svg?style=flat-square)](https://travis-ci.org/bhavingajjar/laravel-api-generator)
[![Quality Score](https://img.shields.io/scrutinizer/g/bhavingajjar/laravel-api-generator.svg?style=flat-square)](https://scrutinizer-ci.com/g/bhavingajjar/laravel-api-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/bhavingajjar/laravel-api-generator.svg?style=flat-square)](https://packagist.org/packages/bhavingajjar/laravel-api-generator)
[![StyleCI](https://github.styleci.io/repos/218828115/shield?branch=master)](https://github.styleci.io/repos/218828115)

This package is used to generate laravel restful api with Requests & Resources

## Installation

You can install the package via composer:

```bash
composer require lararest/laravel-restapi-generator
```

## Publish Configuration File

```bash
php artisan vendor:publish --provider="lararest\\RestApiGenerator\RestApiGeneratorServiceProvider" --tag="config"

Next, if you plan for cross origin support, you should add middleware to your api middleware group within your app/Http/Kernel.php file:
'ApiHeaderInject'

add in env
for allow cross origin support
API_ALLOW_CROSS_ORIGIN = true
for json content type
API_JSON_RESPONSE = true
```

## Usage

``` php
php artisan api:gen --model=User
```

### Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email aymannagy.ahmed@gmail.com instead of using the issue tracker.

## Credits

- [Ayman Nagy Ahmed](https://github.com/AymanNagyAhmed)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.