# PHP Cloudwatch Logger

## Introduction

PHP Cloudwatch Logger is a PHP library designed for logging to the AWS Cloudwatch service. It offers a seamless way to track various types of information from your PHP application directly to AWS Cloudwatch.

## Installation

```
composer require plumthedev/php-cloudwatch-logger
```

## Usage

To instantiate a Monolog logger, utilize the `CloudwatchLoggerFactory::createCloudwatchLogger` method. 
This method initializes a new logger instance, requiring the complete configuration during logger creation.  
_@see [LoggerFactory::createCloudwatchLogger](./src/Logger/LoggerFactory.php)_

## Testing

To run tests, you need to build a Docker image first:

```shell
make build
```

Only then you can execute the tests:

```shell
make test
```

## Contribution

If you spot areas for improvement, wish to make enhancements, or have ideas for further development, feel free to contribute to this project.

To access the project terminal, you must first build the Docker image:

```shell
make build
```

Afterward, you can enter the console:

```shell
make run
```

Before submitting a pull request, ensure everything is in order:

```shell
make check
```

## License

This project is licensed under the terms of the MIT license. See the [LICENSE](LICENSE) file for details.
