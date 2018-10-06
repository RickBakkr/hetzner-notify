<?php
namespace HetznerNotify;

use HetznerNotify\Filter\AbstractFilter;
use HetznerNotify\Filter\Comparator\ComparatorInterface;
use HetznerNotify\Filter\Comparator\GreaterOrEqualComparator;
use HetznerNotify\Filter\Comparator\LessOrEqualComparator;
use HetznerNotify\Filter\FilterInterface;
use HetznerNotify\Filter\PriceFilter;
use HetznerNotify\Filter\RamFilter;

class ServerFilterService
{
    /**
     * array of all filter classes - all new filters must be added here
     */
    const FILTER_CLASSES = [
        'price' => PriceFilter::class,
        'ram'   => RamFilter::class
    ];

    private $servers = [];
    private $filters = [];

    /**
     * ServerFilter constructor.
     * @param array $servers
     * @param array $filters
     * @return ServerFilterService
     */
    public function __construct(array $servers, array $filters)
    {
        $this->servers = $servers;
        $this->filters = $filters;
        return $this;
    }

    /**
     * @return $this
     */
    public function process()
    {
        $result = [];
        foreach ($this->servers as $keyId => $server) {
            /** @var AbstractFilter|FilterInterface $class */
            foreach (self::FILTER_CLASSES as $filterName => $class) {

                $amount = $this->filters[$filterName]['amount'];
                $comparator = $this->getComparatorByOperator($this->filters[$filterName]['operator']);
                /** @var FilterInterface $filter */
                $filter = new $class($filterName, $amount);

                $server = $filter->filter($server, $comparator);

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
     * @param string $operator
     * @return ComparatorInterface
     */
    private function getComparatorByOperator($operator)
    {
        $comparator = null;
        switch ($operator) {
            case '>=':
                $comparator = new GreaterOrEqualComparator();
                break;
            case '<=':
                $comparator = new LessOrEqualComparator();
                break;
            default:
                //default comparator if nothing is set in config
                $comparator = new GreaterOrEqualComparator();
                break;
        }

        return $comparator;
    }
}