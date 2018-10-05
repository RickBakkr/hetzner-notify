<?php

namespace HetznerNotify;

use stdClass;

/**
 * This class reflects a message that is forwarded to external systems (e.g. discord).
 */
class ServerMessage
{
    /** @var stdClass */
    private $server;

    /** @var int */
    private $vat;

    /** @var float */
    private $vatPRC;

    public function __construct(stdClass $server, int $vat = 21)
    {
        $this->server = $server;
        $this->vat = $vat;
        $this->vatPRC = (100 + $vat) / 100;

        return $this;
    }

    /**
     * @return string
     */
    public function asString()
    {
        $flag = ':de:';

        if ($this->server->datacenter[1] == 'HEL') {
            $flag = ':fi:';
        }

        return
            <<<EOT
            ** HETZNER DEAL FOUND **
            {$this->server->freetext}
            CPU: {$this->server->cpu} (Benchmark:
            {$this->server->cpu_benchmark})
            Details: 
            {$this->getDescription($this->server->description)}
            Specials: {$this->getSpecials($this->server->specials)}
            Dedicated is located in {$this->server->datacenter} $flag
            Cost is: € {$this->server->price} (approx. €
            {$this->server->price} * {$this->vatPRC} incl. {$this->vat} % 
            VAT
            https://robot.your-server
            .de/order/marketConfirm/{$this->server->key}
EOT;
    }

    /**
     * @param array $descriptions
     *
     * @return string
     */
    private function getDescription(array $descriptions)
    {
        return implode(PHP_EOL, $descriptions);
    }

    /**
     * @param array $specials
     *
     * @return string
     */
    private function getSpecials(array $specials)
    {
        return implode(' / ', $specials);
    }
}
