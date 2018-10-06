<?php
namespace HetznerNotify\Filter;

use HetznerNotify\Filter\Comparator\ComparatorInterface;
use stdClass;

interface FilterInterface
{
    public function filter(stdClass $server, ComparatorInterface $comparator);
}