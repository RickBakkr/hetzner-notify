<?php
/**
 * Created by PhpStorm.
 * User: dangenendt
 * Date: 05.10.18
 * Time: 21:13
 */

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
            '** HETZNER DEAL FOUND **' . PHP_EOL
            . $this->server->freetext . PHP_EOL
            . 'CPU: ' . $this->server->cpu . ' (Benchmark: ' . $this->server->cpu_benchmark . ')' . PHP_EOL
            . PHP_EOL
            . 'Details: ' . PHP_EOL
            . $this->getDescription($this->server->description)
            . PHP_EOL . PHP_EOL
            . 'Specials: ' . $this->getSpeciaĺs($this->server->specials) . PHP_EOL
            . PHP_EOL
            . 'Dedicated is located in: ' . array_shift($this->server->datacenter) . ' ' . $flag . PHP_EOL
            . PHP_EOL
            . 'Cost is: €' . $this->server->price . ' (approx. €'
            . $this->server->price*$this->vatPRC  . ' incl. ' . $this->vat . '% VAT) ' . PHP_EOL
            . PHP_EOL
            . 'https://robot.your-server.de/order/marketConfirm/' . $this->server->key . PHP_EOL
            . PHP_EOL . PHP_EOL;
    }

    /**
     * @param array $descriptions
     * @return string
     */
    private function getDescription(array $descriptions)
    {
        return implode(PHP_EOL, $descriptions);
    }

    /**
     * @param array $specials
     * @return string
     */
    private function getSpeciaĺs(array $specials)
    {
        return implode(' / ', $specials);
    }
}