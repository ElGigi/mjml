# PHP MJML library

PHP library who use MJML node.js library to convert MJML language to HTML.

## Installation

### Composer

You can install the client with [Composer](https://getcomposer.org/), it's the recommended installation.

```bash
$ composer require elgigi/mjml
$ npm install mjml
```

### Dependencies

* **PHP** >= 7.1
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