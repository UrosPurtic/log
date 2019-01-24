<?php

namespace G4\Log\Adapter;

use G4\Log\AdapterAbstract;
use G4\ValueObject\StringLiteral;

class Redis extends AdapterAbstract
{

    /**
     * @var \Redis
     */
    private $client;

    /**
     * @var StringLiteral
     */
    private $key;

    /**
     * Redis constructor.
     * @param \Redis $client
     */
    public function __construct(\Redis $client, StringLiteral $key)
    {
        $this->client   = $client;
        $this->key      = $key;
    }

    public function save(array $data)
    {
        try {
            $this->appendData($data);
        } catch (\Exception $exception) {
            error_log ($exception->getMessage(), 0);
        }
    }

    public function saveAppend(array $data)
    {
        try {
            $this->appendData($data);
            $this->doRPush();
        } catch (\Exception $exception) {
            error_log ($exception->getMessage(), 0);
        }
    }

    //TODO: Drasko - change this - option to save in two calls !
    public function shouldSaveInOneCall()
    {
        return true;
    }

    private function doRPush()
    {
        $this->client->rPush((string) $this->key, \json_encode($this->getData()));
    }
}