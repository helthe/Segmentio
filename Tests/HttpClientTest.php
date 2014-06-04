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

use Helthe\Component\Segmentio\HttpClient;

class HttpClientTest extends \PHPUnit_Framework_TestCase
{
    public function testSendEmptyData()
    {
        $httpClient = $this->getHttpClientMock();
        $queue = $this->getQueueMock();

        $httpClient->expects($this->once())
                   ->method('post')
                   ->with($this->equalTo('http://api.segment.io/v1/import'), $this->equalTo(array('body' => '{"secret":"foo_key","batch":[]}')));

        $queue->expects($this->once())
              ->method('dequeue')
              ->with($this->equalTo('server'))
              ->will($this->returnValue(null));

        $client = new HttpClient($httpClient, $queue, 'foo_key');

        $client->sendData('foo_id');
    }

    public function testSendDataDoesNotOverwriteUserId()
    {
        $httpClient = $this->getHttpClientMock();
        $method = $this->getMethodMock();
        $queue = $this->getQueueMock();

        $httpClient->expects($this->once())
                   ->method('post')
                   ->with($this->equalTo('http://api.segment.io/v1/import'), $this->equalTo(array('body' => '{"secret":"foo_key","batch":[{"userId":"foo_id","traits":{"foo":"bar"},"action":"identify","secret":"foo_key"}]}')));

        $method->expects($this->once())
               ->method('getArguments')
               ->will($this->returnValue(array('userId' => 'foo_id', 'traits' => array('foo' => 'bar'))));

        $method->expects($this->once())
               ->method('getName')
               ->will($this->returnValue('identify'));

        $queue->expects($this->at(0))
              ->method('dequeue')
              ->with($this->equalTo('server'))
              ->will($this->returnValue($method));

        $queue->expects($this->at(1))
              ->method('dequeue')
              ->with($this->equalTo('server'))
              ->will($this->returnValue(null));

        $client = new HttpClient($httpClient, $queue, 'foo_key');

        $client->sendData('bar_id');
    }

    public function testSendDataDoesNotAddUserId()
    {
        $httpClient = $this->getHttpClientMock();
        $method = $this->getMethodMock();
        $queue = $this->getQueueMock();

        $httpClient->expects($this->once())
                   ->method('post')
                   ->with($this->equalTo('http://api.segment.io/v1/import'), $this->equalTo(array('body' => '{"secret":"foo_key","batch":[{"to":"foo_id","from":"bar_id","action":"alias","secret":"foo_key"}]}')));

        $method->expects($this->once())
               ->method('getArguments')
               ->will($this->returnValue(array('to' => 'foo_id', 'from' => 'bar_id')));

        $method->expects($this->once())
               ->method('getName')
               ->will($this->returnValue('alias'));

        $queue->expects($this->at(0))
              ->method('dequeue')
              ->with($this->equalTo('server'))
              ->will($this->returnValue($method));

        $queue->expects($this->at(1))
              ->method('dequeue')
              ->with($this->equalTo('server'))
              ->will($this->returnValue(null));

        $client = new HttpClient($httpClient, $queue, 'foo_key');

        $client->sendData('foo_bar_id');
    }

    public function testSendDataAddsUserId()
    {
        $httpClient = $this->getHttpClientMock();
        $method = $this->getMethodMock();
        $queue = $this->getQueueMock();

        $httpClient->expects($this->once())
                   ->method('post')
                   ->with($this->equalTo('http://api.segment.io/v1/import'), $this->equalTo(array('body' => '{"secret":"foo_key","batch":[{"event":"foo","properties":{"price":"bar"},"action":"track","secret":"foo_key","userId":"foo_id"}]}')));

        $method->expects($this->once())
               ->method('getArguments')
               ->will($this->returnValue(array('event' => 'foo', 'properties' => array('price' => 'bar'))));

        $method->expects($this->once())
               ->method('getName')
               ->will($this->returnValue('track'));

        $queue->expects($this->at(0))
              ->method('dequeue')
              ->with($this->equalTo('server'))
              ->will($this->returnValue($method));

        $queue->expects($this->at(1))
              ->method('dequeue')
              ->with($this->equalTo('server'))
              ->will($this->returnValue(null));

        $client = new HttpClient($httpClient, $queue, 'foo_key');

        $client->sendData('foo_id');
    }

    public function testSendBatchData()
    {
        $httpClient = $this->getHttpClientMock();
        $method1 = $this->getMethodMock();
        $method2 = $this->getMethodMock();
        $queue = $this->getQueueMock();

        $httpClient->expects($this->once())
                   ->method('post')
                   ->with($this->equalTo('http://api.segment.io/v1/import'), $this->equalTo(array('body' => '{"secret":"foo_key","batch":[{"event":"foo","properties":{"price":"bar"},"action":"track","secret":"foo_key","userId":"foo_id"},{"userId":"foo_id","traits":{"foo":"bar"},"action":"identify","secret":"foo_key"}]}')));

        $method1->expects($this->once())
                ->method('getArguments')
                ->will($this->returnValue(array('event' => 'foo', 'properties' => array('price' => 'bar'))));

        $method1->expects($this->once())
                ->method('getName')
                ->will($this->returnValue('track'));

        $method2->expects($this->once())
                ->method('getArguments')
                ->will($this->returnValue(array('userId' => 'foo_id', 'traits' => array('foo' => 'bar'))));

        $method2->expects($this->once())
                ->method('getName')
                ->will($this->returnValue('identify'));

        $queue->expects($this->at(0))
              ->method('dequeue')
              ->with($this->equalTo('server'))
              ->will($this->returnValue($method1));

        $queue->expects($this->at(1))
              ->method('dequeue')
              ->with($this->equalTo('server'))
              ->will($this->returnValue($method2));

        $queue->expects($this->at(1))
              ->method('dequeue')
              ->with($this->equalTo('server'))
              ->will($this->returnValue(null));

        $client = new HttpClient($httpClient, $queue, 'foo_key');

        $client->sendData('foo_id');
    }

    /**
     * Get a mock of the HTTP client.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getHttpClientMock()
    {
        return $this->getMock('GuzzleHttp\ClientInterface');
    }

    /**
     * Get a mock of the Segment.io method.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getMethodMock()
    {
        return $this->getMock('Helthe\Component\Segmentio\Method\MethodInterface');
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
