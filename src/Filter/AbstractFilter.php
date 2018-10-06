<?php
namespace HetznerNotify\Filter;

abstract class AbstractFilter
{
    /** @var string */
    public $filterName;

    /** @var int */
    public $value = null;

    public function __construct($filterName, $value)
    {
        $this->filterName = $filterName;
        $this->value = $value;
    }
}