# PHP iFDO

A PHP package to read and validate [iFDO v2 files](https://marine-imaging.com/fair/ifdos/iFDO-overview/).

## Installation

```
composer require biigle/ifdo
```

## Usage

```php
use Biigle\Ifdo\Ifdo;

$path = __DIR__ . '/fixtures/ifdo-test-v2.0.0.json';
$obj  = Ifdo::fromFile($path);

// print errors to console if document is no valid
$obj->setDebug(true);

// check if document is valid
$obj->isValid();

// get list of errors if there are any
$obj->getErrors();

// get full json as array
$obj->getJsonData();

// shorthands to safely access info
$obj->getImageSetHeader();
$obj->getImageSetItems();

// use strict mode trigger exceptions for invalid files
$obj = Ifdo::fromString('{"some": "json"}', true);

// get json encoded string
$obj->toString();
```

## Testing

```bash
composer test
```
