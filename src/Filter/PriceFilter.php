<?php
namespace HetznerNotify\Filter;

use HetznerNotify\Filter\Comparator\ComparatorInterface;
use stdClass;

class PriceFilter extends AbstractFilter implements FilterInterface
{
    public function filter(stdClass $server, ComparatorInterface $comparator)
    {
        $name = $this->filterName;
        if ($comparator->compare($this->value, $server->$name)) {
            return $server;
        }

        return null;
    }
}