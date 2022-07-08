# Nova Currency VAT Field

[![Latest Version on Packagist](https://img.shields.io/packagist/v/outl1ne/nova-currency-vat-field.svg?style=flat-square)](https://packagist.org/packages/outl1ne/nova-currency-vat-field)
[![Total Downloads](https://img.shields.io/packagist/dt/outl1ne/nova-currency-vat-field.svg?style=flat-square)](https://packagist.org/packages/outl1ne/nova-currency-vat-field)

This [Laravel Nova](https://nova.laravel.com/) package allows you to create and manage menus and menu items.

## Requirements

- `php: >=8.0`
- `laravel/nova: ^4.0`

## Features

An extension of Nova's default Currency field that provides a checkbox for toggling between VAT and non-VAT prices on the Form view.

Renders the default Currency field on Index and Detail views.

## Screenshots

![Form page](./docs/form.png)

## Installation

Install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require outl1ne/nova-currency-vat-field
```

## Usage

```php
use Outl1ne\NovaCurrencyVatField\CurrencyVAT;

public function fields(Request $request) {
    CurrencyVAT::make('Price', 'price')
      ->VAT(20), // Required, otherwise VAT checkbox isn't rendered
      ->storedWithVAT(), // By default with VAT
      ->storedWithoutVAT()
}
```

## Credits

- [Tarvo Reinpalu](https://github.com/tarpsvo)

## License

Nova Currency VAT Field is open-sourced software licensed under the [MIT license](LICENSE.md).
