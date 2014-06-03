<?php

/*
 * This file is part of the Helthe Segment.io package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Segmentio\Tests;

use Helthe\Component\Segmentio\Queue;

class QueueTest extends \PHPUnit_Framework_TestCase
{
    public function testClear()
    {
        $queue = new Queue();
        $method = $this->getMock('Helthe\Component\Segmentio\Method\MethodInterface');

        $queue->enqueue($method);
        $queue->clear();

        $this->assertSame(null, $queue->dequeue());
    }

    public function testDequeue()
    {
        $method1 = $this->getMock('Helthe\Component\Segmentio\Method\MethodInterface');
        $method1->expects($this->once())
                ->method('supports')
                ->with($this->equalTo('browser'))
                ->will($this->returnValue(true));

        $method2 = $this->getMock('Helthe\Component\Segmentio\Method\MethodInterface');
        $method2->expects($this->never())
                ->method('supports');

        $method3 = $this->getMock('Helthe\Component\Segmentio\Method\MethodInterface');
        $method3->expects($this->at(0))
                ->method('supports')
                ->with($this->equalTo('browser'))
                ->will($this->returnValue(false));
        $method3->expects($this->at(1))
                ->method('supports')
                ->with($this->equalTo('server'))
                ->will($this->returnValue(true));

        $queue = new Queue(array($method1, $method2, $method3));

        $this->assertSame($method1, $queue->dequeue('browser'));
        $this->assertSame($method2, $queue->dequeue());
        $this->assertSame(null, $queue->dequeue('browser'));
        $this->assertSame($method3, $queue->dequeue('server'));
        $this->assertSame(null, $queue->dequeue('server'));
    }

    public function testQueue()
    {
        $queue = new Queue();
        $method = $this->getMock('Helthe\Component\Segmentio\Method\MethodInterface');

        $queue->enqueue($method);

        $this->assertSame($method, $queue->dequeue());
    }
}
