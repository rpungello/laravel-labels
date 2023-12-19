# Changelog

All notable changes to `laravel-labels` will be documented in this file.

## 1.4.0 - 2023-12-19

Add casts for enum fields

## 1.3.0 - 2023-12-19

Add ability to force debugging mode when printing labels

## 1.2.0 - 2023-12-19

Improved code styling, primarily via the use of enums instead of class constants

## 1.1.1 - 2023-08-07

Honor font_size field on fields

## 1.1.0 - 2023-03-08

Add support for Laravel 10.0

## 1.0.6 - 2022-12-20

Fix issue with prematurely adding new pages

## 1.0.5 - 2022-12-19

Fix issues with floating-point math
Fix issue where if the labels exactly filled a page, the last one would be missing

## 1.0.4 - 2022-07-15

Use light gray for debugging boxes and rework how they get drawn.
This was done as there's no need to override Cell and MultiCell since only MultiCell is used, and only in one spot.

## 1.0.3 - 2022-07-14

Remove views from package configuration

## 1.0.2 - 2022-07-14

Add support for printing barcodes

## 1.0.1 - 2022-07-14

Update facade for PhpStorm compatibility

## 1.0.0 - 2022-07-14

Initial release
