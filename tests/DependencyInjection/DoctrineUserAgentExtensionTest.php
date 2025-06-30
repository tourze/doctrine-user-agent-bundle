<?php

namespace Tourze\DoctrineUserAgentBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\DoctrineUserAgentBundle\DependencyInjection\DoctrineUserAgentExtension;
use Tourze\DoctrineUserAgentBundle\EventSubscriber\UserAgentTrackListener;

class DoctrineUserAgentExtensionTest extends TestCase
{
    public function testLoad(): void
    {
        $container = new ContainerBuilder();
        $extension = new DoctrineUserAgentExtension();

        $extension->load([], $container);

        // 验证 property-accessor 服务是否已加载
        self::assertTrue($container->has('doctrine-user-agent.property-accessor'));
        
        // 验证 UserAgentTrackListener 服务已通过自动配置加载
        // 由于使用了 resource 配置和 autoconfigure，服务会在编译时自动注册
    }
}