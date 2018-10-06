<?php
namespace HetznerNotify;

use HetznerNotify\Filter\Comparator\ComparatorFactory;
use HetznerNotify\Filter\Comparator\ComparatorInterface;
use HetznerNotify\Filter\Comparator\GreaterOrEqualComparator;
use HetznerNotify\Filter\Comparator\LessOrEqualComparator;
use HetznerNotify\Filter\Filter;

class ServerFilterService
{
    private $servers = [];
    private $filter = [];

    /**
     * ServerFilter constructor.
     * @param array $servers
     * @param array $filter
     * @return ServerFilterService
     */
    public function __construct(array $servers, array $filter)
    {
        $this->servers = $servers;
        $this->filter = $filter;
        return $this;
    }

    /**
     * executes all configured filter from config
     * @return $this
     */
    public function process()
    {
        $result = [];
        foreach ($this->servers as $keyId => $server) {
            foreach ($this->filter as $filterName => $filterConfig) {
                $value = $this->getFilterValue($filterConfig);
                $comparator = ComparatorFactory::getComparatorByOperator($filterConfig['operator']);
                $filter = new Filter($filterName, $value);
                $server = $filter->process($server, $comparator);
                if (empty($server)) {
                    break;
                }
            }

            if (empty($server)) {
                continue;
            }

            $result[] = $server;
        }

        $this->servers = $result;
        return $this;
    }

    /**
     * @return array
     */
    public function getServers()
    {
        return $this->servers;
    }

    /**
     * @param array $filterConfig
     * @return mixed
     */
    private function getFilterValue(array $filterConfig)
    {
        if (isset($filterConfig['bool'])) {
            return $filterConfig['bool'];
        } else {
            return $filterConfig['amount'];
        }
    }
}