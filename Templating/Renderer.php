<?php

/*
 * This file is part of the Helthe Segment.io package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Segmentio\Templating;

use Helthe\Component\Segmentio\Method\AliasMethod;
use Helthe\Component\Segmentio\Method\IdentifyMethod;
use Helthe\Component\Segmentio\Method\LoadMethod;
use Helthe\Component\Segmentio\Method\MethodInterface;
use Helthe\Component\Segmentio\Method\PageMethod;
use Helthe\Component\Segmentio\Method\TrackMethod;
use Helthe\Component\Segmentio\Queue;

/**
 * Renderer is in charge of rendering all Segment.io browser library code.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Renderer
{
    /**
     * Renders an alias method.
     *
     * @param string $newId
     * @param string $oldId
     *
     * @return string
     */
    public function renderAlias($newId, $oldId = null)
    {
        return $this->renderMethod(new AliasMethod($newId, $oldId));
    }

    /**
     * Renders the asynchronous analytics.js loading script.
     *
     * @return string
     */
    public function renderAsyncScript()
    {
        return 'window.analytics=window.analytics||[],window.analytics.methods=["identify","group","track","page","pageview","alias","ready","on","once","off","trackLink","trackForm","trackClick","trackSubmit"],window.analytics.factory=function(t){return function(){var a=Array.prototype.slice.call(arguments);return a.unshift(t),window.analytics.push(a),window.analytics}};for(var i=0;i<window.analytics.methods.length;i++){var key=window.analytics.methods[i];window.analytics[key]=window.analytics.factory(key)}window.analytics.load=function(t){if(!document.getElementById("analytics-js")){var a=document.createElement("script");a.type="text/javascript",a.id="analytics-js",a.async=!0,a.src=("https:"===document.location.protocol?"https://":"http://")+"cdn.segment.io/analytics.js/v1/"+t+"/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(a,n)}},window.analytics.SNIPPET_VERSION="2.0.9",';
    }

    /**
     * Renders an identify method.
     *
     * @param string $userId
     * @param array  $traits
     *
     * @return string
     */
    public function renderIdentify($userId, array $traits = array())
    {
        return $this->renderMethod(new IdentifyMethod($userId, $traits));
    }

    /**
     * Renders a load method.
     *
     * @param string $key
     *
     * @return string
     */
    public function renderLoad($key)
    {
        return $this->renderMethod(new LoadMethod($key));
    }

    /**
     * Renders the library method.
     *
     * @param MethodInterface $method
     *
     * @return string
     */
    public function renderMethod(MethodInterface $method)
    {
        if (!$method->supports(MethodInterface::BROWSER_PLATFORM)) {
            throw new \InvalidArgumentException('Renderer only accepts browser methods.');
        }

        return 'window.analytics.' . $method->getName() . '(' . $this->renderArguments($method) . ');';
    }

    /**
     * Renders a page method.
     *
     * @param string $name
     * @param string $category
     * @param array  $properties
     *
     * @return string
     */
    public function renderPage($name = null,$category = null,  array $properties = array())
    {
        return $this->renderMethod(new PageMethod($name, $category, $properties));
    }

    /**
     * Renders a queue of library methods.
     *
     * @return string
     */
    public function renderQueue(Queue $queue)
    {
        $render = '';

        while ($method = $queue->dequeue(MethodInterface::BROWSER_PLATFORM)) {
            $render .= $this->renderMethod($method) . "\n";
        }

        return trim($render);
    }

    /**
     * Renders a track method.
     *
     * @param string $event
     * @param array  $properties
     *
     * @return string
     */
    public function renderTrack($event, array $properties = array())
    {
        return $this->renderMethod(new TrackMethod($event, $properties));
    }

    /**
     * Renders the method arguments.
     *
     * @param MethodInterface $method
     *
     * @return string
     */
    private function renderArguments(MethodInterface $method)
    {
        return implode(',', array_map(array($this, 'renderArgument'), $method->getArguments()));
    }

    /**
     * Renders a method argument.
     *
     * @param mixed $argument
     *
     * @return string
     */
    private function renderArgument($argument)
    {
        if (is_array($argument)) {
            $argument = json_encode($argument);
        } elseif (null === $argument) {
            $argument = 'null';
        } elseif (is_string($argument)) {
            $argument = '"' . $argument . '"';
        }

        return $argument;
    }
}
