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

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Helthe\Component\Segmentio\Method\MethodInterface;

/**
 * Segment.io HTTP client for interacting with the HTTP API.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class HttpClient extends Client
{
    /**
     * Guzzle client.
     *
     * @var GuzzleClientInterface
     */
    private $client;

    /**
     * Constructor.
     *
     * @param GuzzleClientInterface $client
     * @param Queue                 $queue
     * @param string                $writeKey
     */
    public function __construct(GuzzleClientInterface $client, Queue $queue, $writeKey)
    {
        parent::__construct($queue, $writeKey);

        $this->client = $client;
    }

    /**
     * Sends the data to Segment.io for the given user id.
     *
     * @param string $userId
     */
    public function sendData($userId)
    {
        $this->client->post('http://api.segment.io/v1/import', array('body' => json_encode(array(
            'secret' => $this->getWriteKey(),
            'batch'  => $this->buildBatch($userId)
        ))));
    }

    /**
     * Builds the batch of method data for the given user id.
     *
     * @param string $userId
     *
     * @return array
     */
    private function buildBatch($userId)
    {
        $batch = array();

        while ($method = $this->getQueue()->dequeue(MethodInterface::SERVER_PLATFORM)) {
            $batch[] = $this->getMethodData($method, $userId);
        }

        return $batch;
    }

    /**
     * Get the method data for the given user id.
     *
     * @param MethodInterface $method
     * @param string          $userId
     *
     * @return array
     */
    private function getMethodData(MethodInterface $method, $userId)
    {
        $data = $method->getArguments();
        $data['action'] = $method->getName();
        $data['secret'] = $this->getWriteKey();

        // Don't overwrite the userId if it it is present.
        if (!isset($data['userId']) && !isset($data['from'])) {
            $data['userId'] = $userId;
        }

        return $data;
    }
}
