<?php

/*
 * This file is part of the Helthe Segment.io package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Segmentio;

use Helthe\Component\Segmentio\Method\AliasMethod;
use Helthe\Component\Segmentio\Method\IdentifyMethod;
use Helthe\Component\Segmentio\Method\PageMethod;
use Helthe\Component\Segmentio\Method\TrackMethod;

/**
 * Segment.io client.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Client implements ClientInterface
{
    /**
     * Method queue.
     *
     * @var Queue
     */
    private $queue;

    /**
     * API key used to send data to Segment.io.
     *
     * @var string
     */
    private $writeKey;

    public function __construct(Queue $queue, $writeKey)
    {
        $this->queue = $queue;
        $this->writeKey = $writeKey;
    }

    /**
     * {@inheritdoc}
     */
    public function alias($newId, $oldId = null)
    {
        $this->queue->enqueue(new AliasMethod($newId, $oldId));
    }

    /**
     * {@inheritdoc}
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * {@inheritdoc}
     */
    public function getWriteKey()
    {
        return $this->writeKey;
    }

    /**
     * {@inheritdoc}
     */
    public function identify($userId, array $traits = array())
    {
        $this->queue->enqueue(new IdentifyMethod($userId, $traits));
    }

    /**
     * {@inheritdoc}
     */
    public function page($category = null, $name = null, array $properties = array())
    {
        $this->queue->enqueue(new PageMethod($category, $name, $properties));
    }

    /**
     * {@inheritdoc}
     */
    public function track($event, array $properties = array())
    {
        $this->queue->enqueue(new TrackMethod($event, $properties));
    }
}
