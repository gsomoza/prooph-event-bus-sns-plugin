<?php

namespace SomozaTest\PSB\Publisher\SNS\Resolver;

use Mockery as m;
use Somoza\PSB\Publisher\SNS\Resolver\MapperTopicResolver;
use SomozaTest\PSB\Publisher\SNS\TestCase;

/**
 * @author Gabriel Somoza <gabriel@somoza.me>
 */
final class MapperTopicResolverTest extends TestCase
{
    const ARN_DEFAULT = 'default_arn';
    const ARN_SPECIFIC = 'specific_arn';
    const ARN_SPECIFIC_DEEP = 'specific_deep';

    /**
     * @return void
     */
    public function testResolveDefault()
    {
        $resolver = new \Somoza\PSB\Publisher\SNS\Resolver\MapperTopicResolver(self::ARN_DEFAULT);

        $this->assertEquals(self::ARN_DEFAULT, $resolver->resolve('Acme\\Foo'));
    }

    /**
     * @return void
     */
    public function testResolveDeepWithPrefix()
    {
        $resolver = new MapperTopicResolver(self::ARN_DEFAULT, 'Acme', [
            'Foo' => self::ARN_SPECIFIC,
            'Foo\\Baz' => self::ARN_SPECIFIC_DEEP,
        ]);

        $this->assertEquals(self::ARN_SPECIFIC, $resolver->resolve('Acme\\Foo\\Bar'));
        $this->assertEquals(self::ARN_SPECIFIC_DEEP, $resolver->resolve('Acme\\Foo\\Baz'));
    }

    /**
     * @return void
     */
    public function testResolveDeepNoPrefix()
    {
        $resolver = new MapperTopicResolver(self::ARN_DEFAULT, '', [
            'Foo' => self::ARN_SPECIFIC,
            'Foo\\Baz' => self::ARN_SPECIFIC_DEEP,
        ]);

        // note that this tests must be the opposite of those in testResolveDeepWithPrefix
        $this->assertNotEquals(self::ARN_SPECIFIC, $resolver->resolve('Acme\\Foo\\Bar'));
        $this->assertNotEquals(self::ARN_SPECIFIC_DEEP, $resolver->resolve('Acme\\Foo\\Baz'));

        $this->assertEquals(self::ARN_DEFAULT, $resolver->resolve('Acme\\Foo\\Bar'));
        $this->assertEquals(self::ARN_SPECIFIC, $resolver->resolve('Foo'));
        $this->assertEquals(self::ARN_SPECIFIC, $resolver->resolve('Foo\\Bar'));
        $this->assertEquals(self::ARN_SPECIFIC_DEEP, $resolver->resolve('Foo\\Baz'));
    }
}
