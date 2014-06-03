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

use Helthe\Component\Segmentio\Client;
use Helthe\Component\Segmentio\Method\AliasMethod;
use Helthe\Component\Segmentio\Method\IdentifyMethod;
use Helthe\Component\Segmentio\Method\PageMethod;
use Helthe\Component\Segmentio\Method\TrackMethod;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testAlias()
    {
        $queue = $this->getQueueMock();

        $queue->expects($this->once())
              ->method('enqueue')
              ->with($this->equalTo(new AliasMethod('foo_id')));

        $client = new Client($queue, 'foo_key');

        $client->alias('foo_id');
    }

    public function testGetQueue()
    {
        $queue = $this->getQueueMock();

        $client = new Client($queue, 'foo_key');

        $this->assertSame($queue, $client->getQueue());
    }

    public function testGetWriteKey()
    {
        $queue = $this->getQueueMock();

        $client = new Client($queue, 'foo_key');

        $this->assertSame('foo_key', $client->getWriteKey());
    }

    public function testIdentify()
    {
        $queue = $this->getQueueMock();

        $queue->expects($this->once())
              ->method('enqueue')
              ->with($this->equalTo(new IdentifyMethod('foo_id')));

        $client = new Client($queue, 'foo_key');

        $client->identify('foo_id');
    }

    public function testPage()
    {
        $queue = $this->getQueueMock();

        $queue->expects($this->once())
              ->method('enqueue')
              ->with($this->equalTo(new PageMethod()));

        $client = new Client($queue, 'foo_key');

        $client->page();
    }

    public function testTrack()
    {
        $queue = $this->getQueueMock();

        $queue->expects($this->once())
              ->method('enqueue')
              ->with($this->equalTo(new TrackMethod('foo')));

        $client = new Client($queue, 'foo_key');

        $client->track('foo');
    }

    /**
     * Get a mock of the Segment.io queue.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getQueueMock()
    {
        return $this->getMock('Helthe\Component\Segmentio\Queue');
    }
}
