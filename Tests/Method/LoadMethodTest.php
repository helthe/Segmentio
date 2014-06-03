<?php

/*
 * This file is part of the Helthe Segment.io package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Segmentio\Tests\Method;

use Helthe\Component\Segmentio\Method\LoadMethod;

class LoadMethodTest extends \PHPUnit_Framework_TestCase
{
    public function testGetArguments()
    {
        $method = new LoadMethod('foo_key');
        $this->assertEquals(array('foo_key'), $method->getArguments());
    }

    public function testGetName()
    {
        $method = new LoadMethod('foo_key');
        $this->assertEquals('load', $method->getName());
    }

    public function testSupports()
    {
        $method = new LoadMethod('foo_key');
        $this->assertTrue($method->supports('browser'));
        $this->assertFalse($method->supports('server'));
        $this->assertFalse($method->supports('bar'));
    }
}
