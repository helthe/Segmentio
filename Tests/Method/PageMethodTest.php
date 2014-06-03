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

use Helthe\Component\Segmentio\Method\PageMethod;

class PageMethodTest extends \PHPUnit_Framework_TestCase
{
    public function testGetArguments()
    {
        $method = new PageMethod();
        $this->assertEquals(array('category' => null, 'name' => null, 'properties' => array()), $method->getArguments());

        $method = new PageMethod('foo');
        $this->assertEquals(array('category' => null, 'name' => 'foo', 'properties' => array()), $method->getArguments());

        $method = new PageMethod('foo', 'bar');
        $this->assertEquals(array('category' => 'bar', 'name' => 'foo', 'properties' => array()), $method->getArguments());

        $method = new PageMethod('foo', 'bar', array('url' => 'http://foor.bar'));
        $this->assertEquals(array('category' => 'bar', 'name' => 'foo', 'properties' => array('url' => 'http://foor.bar')), $method->getArguments());
    }

    public function testGetName()
    {
        $method = new PageMethod('foo_id');
        $this->assertEquals('page', $method->getName());
    }

    public function testSupports()
    {
        $method = new PageMethod('foo_id');
        $this->assertTrue($method->supports('browser'));
        $this->assertFalse($method->supports('server'));
        $this->assertFalse($method->supports('bar'));
    }
}
