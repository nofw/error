# Error handler

[![Latest Version](https://img.shields.io/github/release/nofw/error.svg?style=flat-square)](https://github.com/nofw/error/releases)
[![Build Status](https://img.shields.io/travis/nofw/error.svg?style=flat-square)](https://travis-ci.org/nofw/error)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/nofw/error.svg?style=flat-square)](https://scrutinizer-ci.com/g/nofw/error)
[![Quality Score](https://img.shields.io/scrutinizer/g/nofw/error.svg?style=flat-square)](https://scrutinizer-ci.com/g/nofw/error)
[![Total Downloads](https://img.shields.io/packagist/dt/nofw/error.svg?style=flat-square)](https://packagist.org/packages/nofw/error)

**Common interface for error handlers.**


## Why?

With the introduction of [PSR-3](http://www.php-fig.org/psr/psr-3/) everyone's life became a whole lot easier:
libraries could finally implement proper logging without relying on arbitrary implementations.

Logging however in case of applications is not always that simple. Error monitoring became quite common these days,
and the current PSR-3 logger interface cannot always cover the needs of an error handler.

For example: an error monitor expects backtrace to be sent. With PSR-3 it's only possible,
if you send it as part of the log message or send it in a context.

Futhermore, with PHP 7 we can finally catch errors as well, not just exceptions.


## Install

Via Composer

``` bash
$ composer require nofw/error
```


## Testing

``` bash
$ make test
```


## Security

If you discover any security related issues, please contact us at [mark.sagikazar@gmail.com](mailto:mark.sagikazar@gmail.com).


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
