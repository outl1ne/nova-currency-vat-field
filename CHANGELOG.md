# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.1.0] - 09-10-2026

### Added

- Added `displayedWithVAT()` to have the form open with the VAT checkbox checked and the price shown with VAT, even when it's stored without VAT
- Added `storedDecimals()` to store the price with more decimals than the currency has, so a price entered with VAT and stored without it is not off by a cent when shown again

### Changed

- A field that is saved without changing its value or checkbox now sends back the exact value it was loaded with

### Fixed

- Fixed a price of `0` being saved as `null`
- Fixed `updatesWithCheckbox()` converting the value twice, once in the input and once more when saving

## [3.0.0] - 10-06-2026

### Added

- Added Nova 5 support
- Added a live preview of the inverse price next to the VAT checkbox ("With VAT" / "Without VAT")

### Changed

- Dropped Nova 4 support
- Dropped PHP 8.0 support
- Colors now follow Nova's theme CSS variables instead of Tailwind's built-in palette
- Updated packages

### Fixed

- Fixed dark mode styles never applying, as Tailwind's class prefix was also applied to the `dark` selector
- Fixed missing spacing between the VAT checkbox and its label
- Fixed a crash on zero-decimal currencies (eg. JPY), whose step value has no fraction
- Removed leftover Nova 3 / Tailwind 1 classes that no longer generated any CSS

## [2.0.1] - 23-08-2022

### Changed

- Bump DependentFormField to Nova 4.13 version

## [2.0.0] - 17-08-2022

### Added

- Nova 4 support
- PHP 8 support

### Changed

- Dropped Nova 3 support
- Dropped PHP 7 support
- Renamed namespace from OptimistDigital to outl1ne
- Updated packages

## [1.1.3] - 2021-01-06

### Changed

- Fixed index view component not being rendered (thanks to [@dvdmarchetti](https://github.com/dvdmarchetti))
- Updated packages

## [1.1.2] - 2021-01-06

### Changed

- Fixed `nullable` still not yet working

## [1.1.1] - 2021-01-06

### Changed

- Fixed `undefined` being used instead of `null`
- Updated packages

## [1.1.0] - 2020-12-18

### Added

- Localization support

## [1.0.0] - 2020-12-18

Initial release.
