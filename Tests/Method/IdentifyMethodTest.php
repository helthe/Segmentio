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

use Helthe\Component\Segmentio\Method\IdentifyMethod;

class IdentifyMethodTest extends \PHPUnit_Framework_TestCase
{
    public function testGetArguments()
    {
        $method = new IdentifyMethod('foo_id');
        $this->assertEquals(array('userId' => 'foo_id', 'traits' => array()), $method->getArguments());

        $method = new IdentifyMethod('foo_id', array('foo' => 'bar'));
        $this->assertEquals(array('userId' => 'foo_id', 'traits' => array('foo' => 'bar')), $method->getArguments());
    }

    public function testGetName()
    {
        $method = new IdentifyMethod('foo_id');
        $this->assertEquals('identify', $method->getName());
    }

    public function testSupports()
    {
        $method = new IdentifyMethod('foo_id');
        $this->assertTrue($method->supports('browser'));
        $this->assertTrue($method->supports('server'));
        $this->assertFalse($method->supports('bar'));
    }
}
