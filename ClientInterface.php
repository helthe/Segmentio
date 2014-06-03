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

/**
 * Interface for Segment.io clients.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
interface ClientInterface
{
    /**
     * Associates two different user identities.
     *
     * @param string $newId
     * @param string $oldId
     */
    public function alias($newId, $oldId = null);

    /**
     * Get the method queue managed by the client.
     *
     * @return Queue
     */
    public function getQueue();

    /**
     * Get the API key used to send data to Segment.io.
     *
     * @return string
     */
    public function getWriteKey();

    /**
     * Associates a user with their actions and traits.
     *
     * @param string $userId
     * @param array  $traits
     */
    public function identify($userId, array $traits = array());

    /**
     * Tracks a pageview.
     *
     * @param string $category
     * @param string $name
     * @param array  $properties
     */
    public function page($category = null, $name = null, array $properties = array());

    /**
     * Tracks an event.
     *
     * @param string $event
     * @param array  $properties
     */
    public function track($event, array $properties = array());
}
