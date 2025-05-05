<?php

namespace Tourze\DoctrineUserAgentBundle\Tests\Unit\Attribute;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineUserAgentBundle\Attribute\CreateUserAgentColumn;

class CreateUserAgentColumnTest extends TestCase
{
    public function testAttributeExists(): void
    {
        $attribute = new CreateUserAgentColumn();
        $this->assertInstanceOf(CreateUserAgentColumn::class, $attribute);
    }

    public function testAttributeTarget(): void
    {
        $reflection = new \ReflectionClass(CreateUserAgentColumn::class);
        $attributes = $reflection->getAttributes();

        $this->assertCount(1, $attributes);
        $this->assertEquals(\Attribute::class, $attributes[0]->getName());

        $arguments = $attributes[0]->getArguments();
        $this->assertCount(1, $arguments);
        $this->assertEquals(\Attribute::TARGET_PROPERTY, $arguments[0]);
    }
}
