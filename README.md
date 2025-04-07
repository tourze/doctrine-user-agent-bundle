# Doctrine User Agent Bundle

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-user-agent-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-user-agent-bundle)

A Symfony bundle that automatically tracks and records User-Agent information in Doctrine entities.

## Features

- Automatically captures User-Agent information from HTTP requests
- Provides attributes to mark entity fields for User-Agent tracking
- Supports tracking User-Agent on both entity creation and updates
- Seamless integration with Doctrine ORM events
- Zero configuration required for basic usage

## Installation

```bash
composer require tourze/doctrine-user-agent-bundle
```

## Quick Start

1. Add the bundle to your application's kernel:

```php
// config/bundles.php
return [
    // ...
    Tourze\DoctrineUserAgentBundle\DoctrineUserAgentBundle::class => ['all' => true],
];
```

2. Use the attributes in your entity:

```php
use Tourze\DoctrineUserAgentBundle\Attribute\CreateUserAgentColumn;
use Tourze\DoctrineUserAgentBundle\Attribute\UpdateUserAgentColumn;

class YourEntity
{
    #[CreateUserAgentColumn]
    private ?string $createdUserAgent = null;

    #[UpdateUserAgentColumn]
    private ?string $updatedUserAgent = null;
}
```

## Usage

### Available Attributes

- `#[CreateUserAgentColumn]`: Records User-Agent when the entity is created
- `#[UpdateUserAgentColumn]`: Records User-Agent when the entity is updated

The bundle will automatically capture and store the User-Agent information from the request headers.

## Requirements

- PHP 8.1 or higher
- Symfony 6.4 or higher
- Doctrine ORM Bundle 2.13 or higher

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
