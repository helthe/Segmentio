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

use Helthe\Component\Segmentio\Templating\Renderer;

class RendererTest extends \PHPUnit_Framework_TestCase
{
    public function testRenderAlias()
    {
        $renderer = new Renderer();

        $this->assertEquals('window.analytics.alias("foo_id",null);', $renderer->renderAlias('foo_id'));
        $this->assertEquals('window.analytics.alias("foo_id","bar_id");', $renderer->renderAlias('foo_id', 'bar_id'));
    }

    public function testRenderAsyncScript()
    {
        $renderer = new Renderer();

        $this->assertEquals('window.analytics=window.analytics||[],window.analytics.methods=["identify","group","track","page","pageview","alias","ready","on","once","off","trackLink","trackForm","trackClick","trackSubmit"],window.analytics.factory=function(t){return function(){var a=Array.prototype.slice.call(arguments);return a.unshift(t),window.analytics.push(a),window.analytics}};for(var i=0;i<window.analytics.methods.length;i++){var key=window.analytics.methods[i];window.analytics[key]=window.analytics.factory(key)}window.analytics.load=function(t){if(!document.getElementById("analytics-js")){var a=document.createElement("script");a.type="text/javascript",a.id="analytics-js",a.async=!0,a.src=("https:"===document.location.protocol?"https://":"http://")+"cdn.segment.io/analytics.js/v1/"+t+"/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(a,n)}},window.analytics.SNIPPET_VERSION="2.0.9",', $renderer->renderAsyncScript());
    }

    public function testRenderIdentify()
    {
        $renderer = new Renderer();

        $this->assertEquals('window.analytics.identify("foo_id",[]);', $renderer->renderIdentify('foo_id'));
        $this->assertEquals('window.analytics.identify("foo_id",{"foo":"bar"});', $renderer->renderIdentify('foo_id', array('foo' => 'bar')));
    }

    public function testRenderLoad()
    {
        $renderer = new Renderer();

        $this->assertEquals('window.analytics.load("foo_key");', $renderer->renderLoad('foo_key'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRenderInvalidMethod()
    {
        $method = $this->getMock('Helthe\Component\Segmentio\Method\MethodInterface');
        $renderer = new Renderer();

        $method->expects($this->once())
               ->method('supports')
               ->with($this->equalTo('browser'))
               ->will($this->returnValue(false));

        $renderer->renderMethod($method);
    }

    public function testRenderMethod()
    {
        $method = $this->getMock('Helthe\Component\Segmentio\Method\MethodInterface');
        $renderer = new Renderer();

        $method->expects($this->once())
               ->method('supports')
               ->with($this->equalTo('browser'))
               ->will($this->returnValue(true));

        $method->expects($this->once())
               ->method('getName')
               ->will($this->returnValue('foo'));

        $method->expects($this->once())
               ->method('getArguments')
               ->will($this->returnValue(array('bar', null, array(), array('foo' => 'bar'))));

        $this->assertEquals('window.analytics.foo("bar",null,[],{"foo":"bar"});', $renderer->renderMethod($method));
    }

    public function testRenderPage()
    {
        $renderer = new Renderer();

        $this->assertEquals('window.analytics.page(null,null,[]);', $renderer->renderPage());
        $this->assertEquals('window.analytics.page(null,"foo",[]);', $renderer->renderPage('foo'));
        $this->assertEquals('window.analytics.page("bar","foo",[]);', $renderer->renderPage('foo', 'bar'));
        $this->assertEquals('window.analytics.page("bar","foo",{"url":"http:\/\/foo.bar"});', $renderer->renderPage('foo', 'bar', array('url' => 'http://foo.bar')));
    }

    public function testRenderQueue()
    {
        $method = $this->getMock('Helthe\Component\Segmentio\Method\MethodInterface');
        $queue = $this->getMock('Helthe\Component\Segmentio\Queue');
        $renderer = new Renderer();

        $method->expects($this->once())
               ->method('supports')
               ->with($this->equalTo('browser'))
               ->will($this->returnValue(true));

        $method->expects($this->once())
               ->method('getName')
               ->will($this->returnValue('foo'));

        $method->expects($this->once())
               ->method('getArguments')
               ->will($this->returnValue(array('bar', null, array(), array('foo' => 'bar'))));

        $queue->expects($this->at(0))
              ->method('dequeue')
              ->with($this->equalTo('browser'))
              ->will($this->returnValue($method));

        $queue->expects($this->at(1))
              ->method('dequeue')
              ->with($this->equalTo('browser'))
              ->will($this->returnValue(null));

        $this->assertEquals('window.analytics.foo("bar",null,[],{"foo":"bar"});', $renderer->renderQueue($queue));
    }

    public function testRenderTrack()
    {
        $renderer = new Renderer();

        $this->assertEquals('window.analytics.track("foo",[]);', $renderer->renderTrack('foo'));
        $this->assertEquals('window.analytics.track("foo",{"type":"bar"});', $renderer->renderTrack('foo', array('type' => 'bar')));
    }
}
