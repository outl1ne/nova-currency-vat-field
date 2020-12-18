# Nova Currency VAT Field

[![Latest Version on Packagist](https://img.shields.io/packagist/v/optimistdigital/nova-currency-vat-field.svg?style=flat-square)](https://packagist.org/packages/optimistdigital/nova-currency-vat-field)
[![Total Downloads](https://img.shields.io/packagist/dt/optimistdigital/nova-currency-vat-field.svg?style=flat-square)](https://packagist.org/packages/optimistdigital/nova-currency-vat-field)

This [Laravel Nova](https://nova.laravel.com/) package allows you to create and manage menus and menu items.

## Requirements

- `php: >=7.2`
- `laravel/nova: ^3.0`

## Features

An extension of Nova's default Currency field that provides a checkbox for toggling between VAT and non-VAT prices.

## Screenshots

![Form page](./docs/form.png)
![Details page](./docs/detail.png)

## Installation

Install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require optimistdigital/nova-currency-vat-field
```

## Usage

```php
use OptimistDigital\NovaCurrencyVatField\CurrencyVat;

public function fields(Request $request) {
    CurrencyVat::make('Price', 'price')
      ->vat(20)
      ->storesWithoutVat(),
      ->storesWithVat()
}
```

## Credits

- [Tarvo Reinpalu](https://github.com/tarpsvo)

## License

Nova Currency VAT Field is open-sourced software licensed under the [MIT license](LICENSE.md).
