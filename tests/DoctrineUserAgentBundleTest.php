<?php

declare(strict_types=1);

namespace Tourze\DoctrineUserAgentBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\DoctrineUserAgentBundle\DoctrineUserAgentBundle;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;

/**
 * @internal
 */
#[CoversClass(DoctrineUserAgentBundle::class)]
#[RunTestsInSeparateProcesses]
final class DoctrineUserAgentBundleTest extends AbstractBundleTestCase
{
}
