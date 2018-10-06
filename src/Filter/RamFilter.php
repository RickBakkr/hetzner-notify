<?php
namespace HetznerNotify\Filter;

use HetznerNotify\Filter\Comparator\ComparatorInterface;
use stdClass;

class RamFilter extends AbstractFilter implements FilterInterface
{
    const RAM_FIELD_NAME = 'ram';

    public function filter(stdClass $server, ComparatorInterface $comparator)
    {
        $name = $this->filterName;
        if ($comparator->compare($this->value, $server->$name)) {
            return $server;
        }

        return null;
    }
}