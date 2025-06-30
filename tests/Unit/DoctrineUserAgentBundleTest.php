<?php

namespace Tourze\DoctrineUserAgentBundle\Tests\Unit;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineEntityCheckerBundle\DoctrineEntityCheckerBundle;
use Tourze\DoctrineUserAgentBundle\DoctrineUserAgentBundle;

class DoctrineUserAgentBundleTest extends TestCase
{
    public function testGetBundleDependencies(): void
    {
        $dependencies = DoctrineUserAgentBundle::getBundleDependencies();
        
        $this->assertArrayHasKey(DoctrineBundle::class, $dependencies);
        $this->assertArrayHasKey(DoctrineEntityCheckerBundle::class, $dependencies);
        $this->assertEquals(['all' => true], $dependencies[DoctrineBundle::class]);
        $this->assertEquals(['all' => true], $dependencies[DoctrineEntityCheckerBundle::class]);
    }
    
    public function testBundleInstantiation(): void
    {
        $bundle = new DoctrineUserAgentBundle();
        
        $this->assertInstanceOf(DoctrineUserAgentBundle::class, $bundle);
    }
}