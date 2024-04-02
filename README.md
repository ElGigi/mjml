# PHP MJML library

[![Latest Version](http://img.shields.io/packagist/v/elgigi/mjml.svg?style=flat-square)](https://github.com/ElGigi/mjml/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/github/actions/workflow/status/ElGigi/mjml/tests.yml?branch=main&style=flat-square)](https://github.com/ElGigi/mjml/actions/workflows/tests.yml?query=branch%3Amain)
[![Codacy Grade](https://img.shields.io/codacy/grade/8668d2a2d1d246b2989a4b3b4e46c230.svg?style=flat-square)](https://www.codacy.com/app/ElGigi/mjml?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ElGigi/mjml&amp;utm_campaign=Badge_Grade)
[![Total Downloads](https://img.shields.io/packagist/dt/elgigi/mjml.svg?style=flat-square)](https://packagist.org/packages/elgigi/mjml)

PHP library who use MJML node.js library to convert MJML language to HTML.

## Installation

### Composer

You can install the client with [Composer](https://getcomposer.org/), it's the recommended installation.

```bash
composer require elgigi/mjml
npm install mjml
```

### Dependencies

  * **PHP** ^7.1 || ^8.0
  * NPM: **mjml**

## Usage

```php
$mjml = new Mjml(PATH_TO_MJML_NODE_COMMAND);
$output = $mjml->strToHtml('MY MJML CODE');
```

## Options

You can minify the output (default: yes).

```php
$mjml->minify(false); // To disable minify option
$mjml->minify(true); // To enable minify option
```