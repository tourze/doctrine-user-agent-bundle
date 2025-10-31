<?php

namespace Tourze\DoctrineUserAgentBundle\Attribute;

/**
 * 记录创建记录时的User-Agent
 */
#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class CreateUserAgentColumn
{
}
