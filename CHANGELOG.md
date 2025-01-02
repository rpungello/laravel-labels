# Changelog

All notable changes to `laravel-labels` will be documented in this file.

## 2.2.0 - 2025-01-02

Honor text style

## 2.1.0 - 2024-06-19

Add observer for defining default values for otherwise required fields
This is a convenience that allows user UIs to only prompt for name on creation, then use the update UI for assigning all the otherwise required fields.
This could also have been handled at the database level, but doing it this way makes it so others accessing the database directly don't rely on default values that may otherwise never be set (as there's not necessarily a UI).

## 2.0.0 - 2024-03-21

Enable support for Laravel 11

## 1.7.1 - 2024-03-18

Added helper function to upload PDFs to Laravel disks with random names

## 1.7.0 - 2024-03-18

Added helper function for saving PDF documents to Laravel disks

## 1.6.0 - 2024-03-18

Added helper functions to generate Laravel file responses

## 1.5.0 - 2024-01-25

Allow fields & barcodes to reference customized label model classes

## 1.4.1 - 2024-01-16

Add support for landscape templates

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
