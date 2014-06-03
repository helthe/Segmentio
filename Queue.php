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

use Helthe\Component\Segmentio\Method\MethodInterface;

/**
 * Queue of Segment.io library methods.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Queue
{
    /**
     * The queue.
     *
     * @var MethodInterface[]
     */
    protected $queue;

    /**
     * Constructor.
     *
     * @param array $queue
     */
    public function __construct(array $queue = array())
    {
        $this->clear();

        foreach ($queue as $method) {
            $this->enqueue($method);
        }
    }

    /**
     * Removes all methods from the queue.
     */
    public function clear()
    {
        $this->queue = array();
    }

    /**
     * Removes the method at the beginning of the queue. You can optionally
     * filter to dequeue a specific platform method.
     *
     * @param string $platform
     *
     * @return MethodInterface|null
     */
    public function dequeue($platform = null)
    {
        if (null === $platform) {
            return array_shift($this->queue);
        }

        // The array pointer does not reset between method calls. So we need to reset it manually.
        reset($this->queue);

        do {
            $index = key($this->queue);
            $method = current($this->queue);
            next($this->queue);
        } while ($method instanceof MethodInterface && !$method->supports($platform));

        if (null !== $index) {
            unset($this->queue[$index]);
        }
        if (false === $method) {
            $method = null;
        }

        return $method;
    }

    /**
     * Enqueue a method.
     *
     * @param MethodInterface $method
     */
    public function enqueue(MethodInterface $method)
    {
        $this->queue[] = $method;
    }
}
