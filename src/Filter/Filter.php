<?php
/**
 * Created by PhpStorm.
 * User: dangenendt
 * Date: 06.10.18
 * Time: 18:27
 */

namespace HetznerNotify\Filter;

use HetznerNotify\Filter\Comparator\ComparatorInterface;
use stdClass;

class Filter
{
    /** @var string */
    public $filterName;

    /** @var int */
    public $value = null;

    public function __construct($filterName, $value)
    {
        $this->filterName = $filterName;
        $this->value = $value;

        return $this;
    }

    public function process(stdClass $server, ComparatorInterface $comparator)
    {
        $name = $this->filterName;
        if ($comparator->compare($this->value, $server->$name)) {
            return $server;
        }

        return null;
    }
}