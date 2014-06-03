<?php

/*
 * This file is part of the Helthe Segment.io package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Segmentio\Method;

/**
 * TrackMethod is used to track an action done by a user.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class TrackMethod extends AbstractMethod
{
    /**
     * The event name.
     *
     * @var string
     */
    private $event;

    /**
     * The event properties.
     *
     * @var array
     */
    private $properties;

    /**
     * Constructor.
     *
     * @param string $event
     * @param array  $properties
     */
    public function __construct($event, array $properties = array())
    {
        $this->event = $event;
        $this->properties = $properties;
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return array(
            'event'      => $this->event,
            'properties' => $this->properties,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'track';
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupportedPlatforms()
    {
        return array(
            self::BROWSER_PLATFORM,
            self::SERVER_PLATFORM
        );
    }
}
