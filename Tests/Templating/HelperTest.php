<?php

/*
 * This file is part of the Helthe Segment.io package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Segmentio\Tests\Templating;

use Helthe\Component\Segmentio\Templating\Helper;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    public function testAlias()
    {
        $client = $this->getClientMock();
        $renderer = $this->getRendererMock();

        $renderer->expects($this->once())
                 ->method('renderAlias')
                 ->with($this->equalTo('foo_id'), $this->isNull())
                 ->will($this->returnValue('window.analytics.alias("foo_id");'));

        $helper = new Helper($client, $renderer);

        $this->assertEquals('window.analytics.alias("foo_id");', $helper->alias('foo_id'));
    }

    public function testAsyncScript()
    {
        $client = $this->getClientMock();
        $renderer = $this->getRendererMock();

        $renderer->expects($this->once())
                 ->method('renderAsyncScript')
                 ->will($this->returnValue('window.analytics;'));

        $helper = new Helper($client, $renderer);

        $this->assertEquals('window.analytics;', $helper->asyncScript());
    }

    public function testIdentify()
    {
        $client = $this->getClientMock();
        $renderer = $this->getRendererMock();

        $renderer->expects($this->once())
                 ->method('renderIdentify')
                 ->with($this->equalTo('foo_id'), $this->equalTo(array()))
                 ->will($this->returnValue('window.analytics.identify("foo_id", {});'));

        $helper = new Helper($client, $renderer);

        $this->assertEquals('window.analytics.identify("foo_id", {});', $helper->identify('foo_id'));
    }

    public function testInit()
    {
        $client = $this->getClientMock();
        $renderer = $this->getRendererMock();

        $client->expects($this->once())
               ->method('getWriteKey')
               ->will($this->returnValue('foo_key'));

        $renderer->expects($this->once())
                 ->method('renderAsyncScript')
                 ->will($this->returnValue('window.analytics;'));

        $renderer->expects($this->once())
                 ->method('renderLoad')
                 ->with($this->equalTo('foo_key'))
                 ->will($this->returnValue('window.analytics.load("foo_key");'));

        $helper = new Helper($client, $renderer);

        $this->assertEquals('window.analytics;window.analytics.load("foo_key");', $helper->init());
    }

    public function testPage()
    {
        $client = $this->getClientMock();
        $renderer = $this->getRendererMock();

        $renderer->expects($this->once())
                 ->method('renderPage')
                 ->with($this->equalTo('foo'), $this->equalTo('bar'), $this->equalTo(array()))
                 ->will($this->returnValue('window.analytics.page("foo", "bar", {});'));

        $helper = new Helper($client, $renderer);

        $this->assertEquals('window.analytics.page("foo", "bar", {});', $helper->page('foo', 'bar'));
    }

    public function testQueue()
    {
        $client = $this->getClientMock();
        $queue = $this->getQueueMock();
        $renderer = $this->getRendererMock();

        $client->expects($this->once())
               ->method('getQueue')
               ->will($this->returnValue($queue));

        $renderer->expects($this->once())
                 ->method('renderQueue')
                 ->will($this->returnValue('window.analytics.queue;'));

        $helper = new Helper($client, $renderer);

        $this->assertEquals('window.analytics.queue;', $helper->queue());
    }

    public function testRender()
    {
        $client = $this->getClientMock();
        $queue = $this->getQueueMock();
        $renderer = $this->getRendererMock();

        $client->expects($this->once())
               ->method('getWriteKey')
               ->will($this->returnValue('foo_key'));

        $client->expects($this->once())
               ->method('getQueue')
               ->will($this->returnValue($queue));

        $renderer->expects($this->once())
                 ->method('renderQueue')
                 ->will($this->returnValue('window.analytics.queue;'));

        $renderer->expects($this->once())
                 ->method('renderAsyncScript')
                 ->will($this->returnValue('window.analytics;'));

        $renderer->expects($this->once())
                 ->method('renderLoad')
                 ->with($this->equalTo('foo_key'))
                 ->will($this->returnValue('window.analytics.load("foo_key");'));

        $helper = new Helper($client, $renderer);

        $this->assertEquals('window.analytics;window.analytics.load("foo_key");window.analytics.queue;', $helper->render());
    }

    public function testTrack()
    {
        $client = $this->getClientMock();
        $renderer = $this->getRendererMock();

        $renderer->expects($this->once())
                 ->method('renderTrack')
                 ->with($this->equalTo('foo'), $this->equalTo(array()))
                 ->will($this->returnValue('window.analytics.track("foo", {});'));

        $helper = new Helper($client, $renderer);

        $this->assertEquals('window.analytics.track("foo", {});', $helper->track('foo'));
    }

    /**
     * Get a mock of the Segment.io client.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getClientMock()
    {
        return $this->getMock('Helthe\Component\Segmentio\ClientInterface');
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

    /**
     * Get a mock of the Segment.io renderer.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getRendererMock()
    {
        return $this->getMock('Helthe\Component\Segmentio\Templating\Renderer');
    }
}
