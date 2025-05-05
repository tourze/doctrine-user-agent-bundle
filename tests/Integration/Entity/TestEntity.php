<?php

namespace Tourze\DoctrineUserAgentBundle\Tests\Integration\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineUserAgentBundle\Attribute\CreateUserAgentColumn;
use Tourze\DoctrineUserAgentBundle\Attribute\UpdateUserAgentColumn;

#[ORM\Entity]
#[ORM\Table(name: 'test_entity')]
class TestEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255, nullable: true)]
    #[CreateUserAgentColumn]
    private ?string $createdUserAgent = null;

    #[ORM\Column(length: 255, nullable: true)]
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
}
