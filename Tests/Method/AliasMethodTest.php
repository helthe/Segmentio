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

use Helthe\Component\Segmentio\Method\AliasMethod;

class AliasMethodTest extends \PHPUnit_Framework_TestCase
{
    public function testGetArguments()
    {
        $method = new AliasMethod('foo');
        $this->assertEquals(array('to' => 'foo', 'from' => null), $method->getArguments());

        $method = new AliasMethod('foo', 'bar');
        $this->assertEquals(array('to' => 'foo', 'from' => 'bar'), $method->getArguments());
    }

    public function testGetName()
    {
        $method = new AliasMethod('foo');
        $this->assertEquals('alias', $method->getName());
    }

    public function testSupports()
    {
        $method = new AliasMethod('foo');
        $this->assertTrue($method->supports('browser'));
        $this->assertTrue($method->supports('server'));
        $this->assertFalse($method->supports('bar'));
    }
}
