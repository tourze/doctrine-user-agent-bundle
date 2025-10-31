<?php

namespace Tourze\DoctrineUserAgentBundle\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineUserAgentBundle\Attribute\CreateUserAgentColumn;

/**
 * 自动记录创建和编辑时的UserAgent信息.
 *
 * 有一些记录，我们希望尽可能保存现场信息，以方便后续进行数据分析，就可以使用这个Trait
 */
trait CreatedByUAAware
{
    #[CreateUserAgentColumn]
    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '创建时UA'])]
    private ?string $createdFromUa = null;

    public function getCreatedFromUa(): ?string
    {
        return $this->createdFromUa;
    }

    public function setCreatedFromUa(?string $createdFromUa): void
    {
        $this->createdFromUa = $createdFromUa;
    }
}
