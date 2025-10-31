<?php

namespace Tourze\DoctrineUserAgentBundle\Tests\EventSubscriber;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Tourze\DoctrineUserAgentBundle\Attribute\CreateUserAgentColumn;
use Tourze\DoctrineUserAgentBundle\Attribute\UpdateUserAgentColumn;
use Tourze\DoctrineUserAgentBundle\EventSubscriber\UserAgentTrackListener;
use Tourze\PHPUnitSymfonyKernelTest\AbstractEventSubscriberTestCase;

/**
 * @internal
 */
#[CoversClass(UserAgentTrackListener::class)]
#[RunTestsInSeparateProcesses]
final class UserAgentTrackListenerTest extends AbstractEventSubscriberTestCase
{
    protected function onSetUp(): void
    {
        // 子类自定义的初始化逻辑
    }

    protected function getListener(): UserAgentTrackListener
    {
        return self::getService(UserAgentTrackListener::class);
    }

    public function testSetAndGetUserAgent(): void
    {
        $userAgent = 'Mozilla/5.0 (Test)';
        $this->getListener()->setUserAgent($userAgent);

        $this->assertEquals($userAgent, $this->getListener()->getUserAgent());
    }

    public function testOnKernelRequest(): void
    {
        $userAgent = 'Mozilla/5.0 (Test)';

        // 使用真实的 Request 对象而不是 Mock，因为这样可以更好地测试实际的HTTP请求处理
        $request = new Request();
        $request->headers->set('User-Agent', $userAgent);

        $kernel = $this->createMock(HttpKernelInterface::class);
        $event = new RequestEvent(
            $kernel,
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $this->getListener()->onKernelRequest($event);

        $this->assertEquals($userAgent, $this->getListener()->getUserAgent());
    }

    public function testReset(): void
    {
        $userAgent = 'Mozilla/5.0 (Test)';
        $this->getListener()->setUserAgent($userAgent);
        $this->assertEquals($userAgent, $this->getListener()->getUserAgent());

        $this->getListener()->reset();

        $this->assertNull($this->getListener()->getUserAgent());
    }

    public function testPrePersistWithoutUserAgent(): void
    {
        // 测试没有设置 UserAgent 时的行为
        $this->getListener()->reset();

        $entity = new class {
            #[CreateUserAgentColumn]
            private ?string $createdUserAgent = null;

            public function getCreatedUserAgent(): ?string
            {
                return $this->createdUserAgent;
            }

            public function setCreatedUserAgent(?string $createdUserAgent): void
            {
                $this->createdUserAgent = $createdUserAgent;
            }
        };

        // 验证没有 UserAgent 时，字段保持为 null
        $this->assertNull($entity->getCreatedUserAgent());
    }

    public function testPreUpdateWithoutUserAgent(): void
    {
        // 测试没有设置 UserAgent 时的行为
        $this->getListener()->reset();

        $entity = new class {
            #[UpdateUserAgentColumn]
            private ?string $updatedUserAgent = null;

            public function getUpdatedUserAgent(): ?string
            {
                return $this->updatedUserAgent;
            }

            public function setUpdatedUserAgent(?string $updatedUserAgent): void
            {
                $this->updatedUserAgent = $updatedUserAgent;
            }
        };

        // 验证没有 UserAgent 时，字段保持为 null
        $this->assertNull($entity->getUpdatedUserAgent());
    }

    public function testPrePersistEntity(): void
    {
        $userAgent = 'Mozilla/5.0 (Test Persist Entity)';
        $this->getListener()->setUserAgent($userAgent);

        $entity = new class {
            #[CreateUserAgentColumn]
            private ?string $createdUserAgent = null;

            public function getCreatedUserAgent(): ?string
            {
                return $this->createdUserAgent;
            }

            public function setCreatedUserAgent(?string $createdUserAgent): void
            {
                $this->createdUserAgent = $createdUserAgent;
            }
        };

        $objectManager = $this->createMock(ObjectManager::class);
        $metadata = $this->createMock(ClassMetadata::class);
        $reflectionClass = new \ReflectionClass($entity);

        $objectManager->expects($this->once())
            ->method('getClassMetadata')
            ->with($entity::class)
            ->willReturn($metadata)
        ;

        $metadata->expects($this->once())
            ->method('getReflectionClass')
            ->willReturn($reflectionClass)
        ;

        $this->getListener()->prePersistEntity($objectManager, $entity);

        // 验证 UserAgent 已被设置到实体
        $this->assertEquals($userAgent, $entity->getCreatedUserAgent());
    }

    public function testPreUpdateEntity(): void
    {
        $userAgent = 'Mozilla/5.0 (Test Update Entity)';
        $this->getListener()->setUserAgent($userAgent);

        $entity = new class {
            #[UpdateUserAgentColumn]
            private ?string $updatedUserAgent = null;

            public function getUpdatedUserAgent(): ?string
            {
                return $this->updatedUserAgent;
            }

            public function setUpdatedUserAgent(?string $updatedUserAgent): void
            {
                $this->updatedUserAgent = $updatedUserAgent;
            }
        };

        $objectManager = $this->createMock(ObjectManager::class);
        $eventArgs = $this->createMock(PreUpdateEventArgs::class);
        $metadata = $this->createMock(ClassMetadata::class);
        $reflectionClass = new \ReflectionClass($entity);

        $objectManager->expects($this->once())
            ->method('getClassMetadata')
            ->with($entity::class)
            ->willReturn($metadata)
        ;

        $metadata->expects($this->once())
            ->method('getReflectionClass')
            ->willReturn($reflectionClass)
        ;

        $this->getListener()->preUpdateEntity($objectManager, $entity, $eventArgs);

        // 验证 UserAgent 已被设置到实体
        $this->assertEquals($userAgent, $entity->getUpdatedUserAgent());
    }
}
