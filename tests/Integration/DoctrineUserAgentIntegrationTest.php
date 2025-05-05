<?php

namespace Tourze\DoctrineUserAgentBundle\Tests\Integration;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Tourze\DoctrineUserAgentBundle\EventSubscriber\UserAgentTrackListener;
use Tourze\DoctrineUserAgentBundle\Tests\Integration\Entity\TestEntity;

class DoctrineUserAgentIntegrationTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private UserAgentTrackListener $userAgentTrackListener;

    protected static function getKernelClass(): string
    {
        return IntegrationTestKernel::class;
    }

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine')->getManager();
        $this->userAgentTrackListener = $container->get(UserAgentTrackListener::class);

        // 创建测试数据库结构 - 先删除再创建
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        try {
            $schemaTool->dropSchema($metadata);
        } catch (\Exception $e) {
            // 忽略表不存在的错误
        }

        $schemaTool->createSchema($metadata);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    public function testCreateUserAgentColumn(): void
    {
        // 模拟请求事件
        $userAgent = 'Mozilla/5.0 (Test UserAgent)';
        $request = new Request();
        $request->headers->set('User-Agent', $userAgent);

        $event = new RequestEvent(
            self::$kernel,
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $this->userAgentTrackListener->onKernelRequest($event);

        // 创建新实体
        $entity = new TestEntity();
        $entity->setName('Test Entity');

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        // 断言创建用户代理字段已设置
        $this->assertEquals($userAgent, $entity->getCreatedUserAgent());
        $this->assertNull($entity->getUpdatedUserAgent());
    }

    public function testUpdateUserAgentColumn(): void
    {
        // 重置 UserAgent (确保创建时没有设置)
        $this->userAgentTrackListener->reset();

        // 创建一个没有 UserAgent 的实体
        $entity = new TestEntity();
        $entity->setName('Test Entity');

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        // 设置新的 UserAgent 用于更新
        $updateUserAgent = 'Mozilla/5.0 (Update UserAgent)';
        $request = new Request();
        $request->headers->set('User-Agent', $updateUserAgent);

        $event = new RequestEvent(
            self::$kernel,
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $this->userAgentTrackListener->onKernelRequest($event);

        // 更新实体
        $entity->setName('Updated Test Entity');

        $this->entityManager->flush();

        // 断言更新用户代理字段已设置，创建用户代理字段为null
        $this->assertNull($entity->getCreatedUserAgent());
        $this->assertEquals($updateUserAgent, $entity->getUpdatedUserAgent());
    }

    public function testUserAgentNotSet(): void
    {
        // 重置 UserAgent
        $this->userAgentTrackListener->reset();

        // 创建新实体
        $entity = new TestEntity();
        $entity->setName('No UserAgent Entity');

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        // 断言用户代理字段为空
        $this->assertNull($entity->getCreatedUserAgent());
        $this->assertNull($entity->getUpdatedUserAgent());
    }
}
