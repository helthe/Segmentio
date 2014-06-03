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

use Helthe\Component\Segmentio\Method\TrackMethod;

class TrackMethodTest extends \PHPUnit_Framework_TestCase
{
    public function testGetArguments()
    {
        $method = new TrackMethod('foo');
        $this->assertEquals(array('event' => 'foo', 'properties' => array()), $method->getArguments());

        $method = new TrackMethod('foo', array('price' => 'bar'));
        $this->assertEquals(array('event' => 'foo', 'properties' => array('price' => 'bar')), $method->getArguments());
    }

    public function testGetName()
    {
        $method = new TrackMethod('foo_id');
        $this->assertEquals('track', $method->getName());
    }

    public function testSupports()
    {
        $method = new TrackMethod('foo_id');
        $this->assertTrue($method->supports('browser'));
        $this->assertTrue($method->supports('server'));
        $this->assertFalse($method->supports('bar'));
    }
}
