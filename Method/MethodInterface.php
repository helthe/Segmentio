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
 * Interface used to represent Segment.io library methods.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
interface MethodInterface
{
    /**
     * Identifies the method as a browser method.
     *
     * @var string
     */
    const BROWSER_PLATFORM = 'browser';

    /**
     * Identifies the method as a server method.
     *
     * @var string
     */
    const SERVER_PLATFORM = 'server';

    /**
     * Get the arguments passed to the method.
     *
     * @return array
     */
    public function getArguments();

    /**
     * Get the method name.
     *
     * @return string
     */
    public function getName();

    /**
     * Checks if the method supports the given platform.
     *
     * @param string $platform
     *
     * @return bool
     */
    public function supports($platform);
}
