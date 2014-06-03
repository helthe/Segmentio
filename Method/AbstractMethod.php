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
 * Base class for Segment.io library methods.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
abstract class AbstractMethod implements MethodInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports($platform)
    {
        return in_array($platform, $this->getSupportedPlatforms());
    }

    /**
     * Get the platforms that the method supports.
     *
     * @return array
     */
    abstract protected function getSupportedPlatforms();
}
