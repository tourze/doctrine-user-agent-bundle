<?php

namespace Tourze\DoctrineUserAgentBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineUserAgentBundle\Attribute\CreateUserAgentColumn;
use Tourze\DoctrineUserAgentBundle\Attribute\UpdateUserAgentColumn;

/**
 * 测试实体类
 */
#[ORM\Entity]
#[ORM\Table(name: 'test_entity', options: ['comment' => '测试实体表'])]
class TestEntity implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['comment' => '主键ID'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, options: ['comment' => '实体名称'])]
    private string $name;

    #[ORM\Column(length: 255, nullable: true, options: ['comment' => '创建时的用户代理'])]
    #[CreateUserAgentColumn]
    private ?string $createdUserAgent = null;

    #[ORM\Column(length: 255, nullable: true, options: ['comment' => '更新时的用户代理'])]
    #[UpdateUserAgentColumn]
    private ?string $updatedUserAgent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getCreatedUserAgent(): ?string
    {
        return $this->createdUserAgent;
    }

    public function setCreatedUserAgent(?string $createdUserAgent): self
    {
        $this->createdUserAgent = $createdUserAgent;
        return $this;
    }

    public function getUpdatedUserAgent(): ?string
    {
        return $this->updatedUserAgent;
    }

    public function setUpdatedUserAgent(?string $updatedUserAgent): self
    {
        $this->updatedUserAgent = $updatedUserAgent;
        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
