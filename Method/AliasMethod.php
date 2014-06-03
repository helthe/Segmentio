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
 * AliasMethod is used to associate two different user identities.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class AliasMethod extends AbstractMethod
{
    /**
     * The user's new ID.
     *
     * @var string
     */
    private $newId;

    /**
     * The user's old ID.
     *
     * @var string
     */
    private $oldId;

    /**
     * Constructor.
     *
     * @param string $newId
     * @param string $oldId
     */
    public function __construct($newId, $oldId = null)
    {
        $this->newId = $newId;
        $this->oldId = $oldId;
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return array(
            'to'   => $this->newId,
            'from' => $this->oldId,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'alias';
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
