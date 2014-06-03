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

use Helthe\Component\Segmentio\ClientInterface;

/**
 * Helper exposes read-only methods for use by templating engines.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Helper
{
    /**
     * Segment.io client
     *
     * @var ClientInterface
     */
    private $client;

    /**
     * Segment.io renderer.
     *
     * @var Renderer
     */
    private $renderer;

    /**
     * Constructor.
     *
     * @param ClientInterface $client
     * @param Renderer        $renderer
     */
    public function __construct(ClientInterface $client, Renderer $renderer)
    {
        $this->client = $client;
        $this->renderer = $renderer;
    }

    /**
     * Renders the alias method.
     *
     * @param string $newId
     * @param string $oldId
     *
     * @return string
     */
    public function alias($newId, $oldId = null)
    {
        return $this->renderer->renderAlias($newId, $oldId);
    }

    /**
     * Renders the asynchronous analytics.js loading script.
     *
     * @return string
     */
    public function asyncScript()
    {
        return $this->renderer->renderAsyncScript();
    }

    /**
     * Renders the identify method.
     *
     * @param string $userId
     * @param array  $traits
     *
     * @return string
     */
    public function identify($userId, array $traits = array())
    {
        return $this->renderer->renderIdentify($userId, $traits);
    }

    /**
     * Renders all the code necessary for initializing analytics.js.
     *
     * @return string
     */
    public function init()
    {
        $render = $this->asyncScript();
        $render .= $this->load($this->client->getWriteKey());

        return $render;
    }

    /**
     * Renders the load method.
     *
     * @param string $key
     *
     * @return string
     */
    public function load($key)
    {
        return $this->renderer->renderLoad($key);
    }

    /**
     * Renders the page method.
     *
     * @param string $name
     * @param string $category
     * @param array  $properties
     *
     * @return string
     */
    public function page($name = null, $category = null, array $properties = array())
    {
        return $this->renderer->renderPage($name, $category, $properties);
    }

    /**
     * Renders the method queue stored in the client.
     *
     * @return string
     */
    public function queue()
    {
        return $this->renderer->renderQueue($this->client->getQueue());
    }

    /**
     * Renders all the analytics.js code.
     *
     * @return string
     */
    public function render()
    {
        $render = $this->init();
        $render .= $this->queue();

        return $render;
    }

    /**
     * Renders the track method.
     *
     * @param string $event
     * @param array  $properties
     *
     * @return string
     */
    public function track($event, array $properties = array())
    {
        return $this->renderer->renderTrack($event, $properties);
    }
}
