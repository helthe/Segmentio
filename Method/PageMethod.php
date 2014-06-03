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
 * PageMethod is used to track a pageview.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class PageMethod extends AbstractMethod
{
    /**
     * The page name.
     *
     * @var string
     */
    private $name;

    /**
     * The page category.
     *
     * @var string
     */
    private $category;

    /**
     * The page properties.
     *
     * @var array
     */
    private $properties;

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $category
     * @param array  $properties
     */
    public function __construct($name = null, $category = null, array $properties = array())
    {
        $this->category = $category;
        $this->name = $name;
        $this->properties = $properties;
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return array(
            'category'   => $this->category,
            'name'       => $this->name,
            'properties' => $this->properties,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'page';
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupportedPlatforms()
    {
        return array(
            self::BROWSER_PLATFORM
        );
    }
}
