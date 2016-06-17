<?php

namespace SomozaTest\PSB\Publisher\SNS;

/**
 * @author Gabriel Somoza <gabriel@somoza.me>
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function tearDown()
    {
        \Mockery::close();
    }
}
