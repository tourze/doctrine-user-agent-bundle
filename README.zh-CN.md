# Doctrine User Agent Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-user-agent-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-user-agent-bundle)

一个用于自动跟踪和记录 Doctrine 实体中 User-Agent 信息的 Symfony Bundle。

## 特性

- 自动从 HTTP 请求中捕获 User-Agent 信息
- 提供属性标记用于跟踪 User-Agent 的实体字段
- 支持在实体创建和更新时跟踪 User-Agent
- 与 Doctrine ORM 事件无缝集成
- 基础使用无需配置

## 安装

```bash
composer require tourze/doctrine-user-agent-bundle
```

## 快速开始

1. 将 bundle 添加到应用程序的 kernel 中：

```php
// config/bundles.php
return [
    // ...
    Tourze\DoctrineUserAgentBundle\DoctrineUserAgentBundle::class => ['all' => true],
];
```

2. 在实体中使用属性：

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

## 使用说明

### 可用属性

- `#[CreateUserAgentColumn]`：记录实体创建时的 User-Agent
- `#[UpdateUserAgentColumn]`：记录实体更新时的 User-Agent

Bundle 会自动从请求头中捕获并存储 User-Agent 信息。

## 系统要求

- PHP 8.1 或更高版本
- Symfony 6.4 或更高版本
- Doctrine ORM Bundle 2.13 或更高版本

## 贡献

详情请参阅 [CONTRIBUTING.md](CONTRIBUTING.md)。

## 许可证

MIT 许可证 (MIT)。详情请参阅 [License 文件](LICENSE)。
