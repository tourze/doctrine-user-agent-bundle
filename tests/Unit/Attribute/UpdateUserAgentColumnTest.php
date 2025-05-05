<?php

namespace Tourze\DoctrineUserAgentBundle\Tests\Unit\Attribute;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineUserAgentBundle\Attribute\UpdateUserAgentColumn;

class UpdateUserAgentColumnTest extends TestCase
{
    public function testAttributeExists(): void
    {
        $attribute = new UpdateUserAgentColumn();
        $this->assertInstanceOf(UpdateUserAgentColumn::class, $attribute);
    }

    public function testAttributeTarget(): void
    {
        $reflection = new \ReflectionClass(UpdateUserAgentColumn::class);
        $attributes = $reflection->getAttributes();

        $this->assertCount(1, $attributes);
        $this->assertEquals(\Attribute::class, $attributes[0]->getName());

        $arguments = $attributes[0]->getArguments();
        $this->assertCount(1, $arguments);
        $this->assertEquals(\Attribute::TARGET_PROPERTY, $arguments[0]);
    }
}
