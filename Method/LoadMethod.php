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
 * LoadMethod is used to load the Segment.io library.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class LoadMethod extends AbstractMethod
{
    /**
     * The Segment.io write key.
     *
     * @var string
     */
    private $key;

    /**
     * Constructor.
     *
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return array(
            $this->key,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'load';
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupportedPlatforms()
    {
        return array(
            self::BROWSER_PLATFORM,
        );
    }
}
