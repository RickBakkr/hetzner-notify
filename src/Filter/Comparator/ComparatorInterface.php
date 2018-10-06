<?php
/**
 * Created by PhpStorm.
 * User: dangenendt
 * Date: 06.10.18
 * Time: 09:12
 */

namespace HetznerNotify\Filter\Comparator;

Interface ComparatorInterface
{
    public function compare($valueToCompare, $serverValue);
}