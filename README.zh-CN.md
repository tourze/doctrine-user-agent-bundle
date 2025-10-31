# Doctrine User Agent Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-user-agent-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-user-agent-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/doctrine-user-agent-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-user-agent-bundle)
[![PHP Version Require](https://img.shields.io/packagist/dependency-v/tourze/doctrine-user-agent-bundle/php?style=flat-square)](https://packagist.org/packages/tourze/doctrine-user-agent-bundle)
[![License](https://img.shields.io/packagist/l/tourze/doctrine-user-agent-bundle?style=flat-square)](https://packagist.org/packages/tourze/doctrine-user-agent-bundle)
[![代码覆盖率](https://codecov.io/gh/tourze/doctrine-user-agent-bundle/branch/master/graph/badge.svg)](https://codecov.io/gh/tourze/doctrine-user-agent-bundle)

一个用于自动捕获和记录 Doctrine 实体中 User-Agent 信息的 Symfony Bundle，为数据分析和审计提供支持。

## 特性

- 🚀 **零配置** - 使用简单属性即可开箱即用
- 📱 **自动检测** - 自动从 HTTP 请求中捕获 User-Agent
- 🏷️ **基于属性** - 简单的 PHP 8+ 属性标记实体属性
- ⚡ **事件驱动** - 与 Doctrine ORM 生命周期事件无缝集成
- 🔄 **创建和更新** - 分别跟踪实体创建和修改
- 🧩 **即用 Traits** - 预建的 traits 满足常见用例
- 🔒 **非侵入性** - 仅在属性为 null 时设置值

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

3. 或者使用便捷的 trait：

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

    // 您的其他属性...
}
```

## 使用说明

### 可用属性

#### `#[CreateUserAgentColumn]`
在实体首次持久化到数据库时记录 User-Agent 头。

```php
#[CreateUserAgentColumn]
#[ORM\Column(type: Types::TEXT, nullable: true)]
private ?string $createdUserAgent = null;
```

#### `#[UpdateUserAgentColumn]`
在实体更新时记录 User-Agent 头。

```php
#[UpdateUserAgentColumn]
#[ORM\Column(type: Types::TEXT, nullable: true)]
private ?string $updatedUserAgent = null;
```

### 可用 Traits

#### `CreatedByUAAware`
一个即用的 trait，添加了具有适当 Doctrine 映射的 `createdFromUa` 属性：

```php
use Tourze\DoctrineUserAgentBundle\Traits\CreatedByUAAware;

class MyEntity
{
    use CreatedByUAAware;
    
    // 访问 User-Agent
    public function getCreatedFromUa(): ?string
    {
        return $this->createdFromUa;
    }
}
```

### 工作原理

1. **请求捕获**：Bundle 监听 Symfony 的 `KernelEvents::REQUEST` 并捕获 `User-Agent` 头
2. **实体事件**：当 Doctrine 触发 `prePersist` 或 `preUpdate` 事件时，Bundle 检查标记的属性
3. **值分配**：仅对当前为 `null` 的属性分配 User-Agent 值
4. **非侵入性**：从不覆盖现有值

### 使用场景

- **数据分析**：跟踪哪些浏览器/设备正在创建内容
- **审计日志**：维护实体修改的详细记录
- **安全性**：监控可疑的 User-Agent 模式
- **用户体验**：了解用户的技术偏好

## 系统要求

- PHP 8.1 或更高版本
- Symfony 6.4 或更高版本
- Doctrine ORM 3.0 或更高版本
- Doctrine DBAL 4.0 或更高版本
- Doctrine Bundle 2.13 或更高版本

## 贡献

详情请参阅 [CONTRIBUTING.md](CONTRIBUTING.md)。

## 许可证

MIT 许可证 (MIT)。详情请参阅 [License 文件](LICENSE)。
