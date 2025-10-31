# Doctrine User Agent Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-user-agent-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-user-agent-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/doctrine-user-agent-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-user-agent-bundle)
[![PHP Version Require](https://img.shields.io/packagist/dependency-v/tourze/doctrine-user-agent-bundle/php?style=flat-square)](https://packagist.org/packages/tourze/doctrine-user-agent-bundle)
[![License](https://img.shields.io/packagist/l/tourze/doctrine-user-agent-bundle?style=flat-square)](https://packagist.org/packages/tourze/doctrine-user-agent-bundle)
[![Code Coverage](https://codecov.io/gh/tourze/doctrine-user-agent-bundle/branch/master/graph/badge.svg)](https://codecov.io/gh/tourze/doctrine-user-agent-bundle)

A Symfony bundle that automatically captures and records User-Agent information in Doctrine entities for analytics and audit purposes.

## Features

- 🚀 **Zero Configuration** - Works out of the box with simple attributes
- 📱 **Auto-Detection** - Automatically captures User-Agent from HTTP requests
- 🏷️ **Attribute-Based** - Simple PHP 8+ attributes to mark entity properties
- ⚡ **Event-Driven** - Seamless integration with Doctrine ORM lifecycle events
- 🔄 **Create & Update** - Separate tracking for entity creation and modification
- 🧩 **Ready-to-Use Traits** - Pre-built traits for common use cases
- 🔒 **Non-Intrusive** - Only sets values when properties are null

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

2. Use attributes in your entity:

```php
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineUserAgentBundle\Attribute\CreateUserAgentColumn;
use Tourze\DoctrineUserAgentBundle\Attribute\UpdateUserAgentColumn;

#[ORM\Entity]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[CreateUserAgentColumn]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $createdUserAgent = null;

    #[UpdateUserAgentColumn]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $updatedUserAgent = null;

    // Getters and setters...
}
```

3. Or use the convenience trait:

```php
use Tourze\DoctrineUserAgentBundle\Traits\CreatedByUAAware;

#[ORM\Entity]
class Article
{
    use CreatedByUAAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Your other properties...
}
```

## Usage

### Available Attributes

#### `#[CreateUserAgentColumn]`
Records the User-Agent header when the entity is first persisted to the database.

```php
#[CreateUserAgentColumn]
#[ORM\Column(type: Types::TEXT, nullable: true)]
private ?string $createdUserAgent = null;
```

#### `#[UpdateUserAgentColumn]`
Records the User-Agent header when the entity is updated.

```php
#[UpdateUserAgentColumn]
#[ORM\Column(type: Types::TEXT, nullable: true)]
private ?string $updatedUserAgent = null;
```

### Available Traits

#### `CreatedByUAAware`
A ready-to-use trait that adds a `createdFromUa` property with proper Doctrine mapping:

```php
use Tourze\DoctrineUserAgentBundle\Traits\CreatedByUAAware;

class MyEntity
{
    use CreatedByUAAware;
    
    // Access the User-Agent
    public function getCreatedFromUa(): ?string
    {
        return $this->createdFromUa;
    }
}
```

### How It Works

1. **Request Capture**: The bundle listens to Symfony's `KernelEvents::REQUEST` and captures the `User-Agent` header
2. **Entity Events**: When Doctrine triggers `prePersist` or `preUpdate` events, the bundle checks for marked properties
3. **Value Assignment**: Only assigns User-Agent values to properties that are currently `null`
4. **Non-Intrusive**: Existing values are never overwritten

### Use Cases

- **Analytics**: Track which browsers/devices are creating content
- **Audit Logging**: Maintain detailed records of entity modifications
- **Security**: Monitor suspicious User-Agent patterns
- **User Experience**: Understand your users' technology preferences

## Requirements

- PHP 8.1 or higher
- Symfony 6.4 or higher
- Doctrine ORM 3.0 or higher
- Doctrine DBAL 4.0 or higher
- Doctrine Bundle 2.13 or higher

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
