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
 * IdentifyMethod is used to associate a user with their actions and traits.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class IdentifyMethod extends AbstractMethod
{
    /**
     * The user traits.
     *
     * @var array
     */
    private $traits;

    /**
     * The user's ID.
     *
     * @var string
     */
    private $userId;

    /**
     * Constructor.
     *
     * @param string $userId
     * @param array  $traits
     */
    public function __construct($userId, array $traits = array())
    {
        $this->traits = $traits;
        $this->userId = $userId;
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return array(
            'userId' => $this->userId,
            'traits' => $this->traits,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'identify';
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
