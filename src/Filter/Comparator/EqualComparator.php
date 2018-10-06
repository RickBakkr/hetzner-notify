<?php
/**
 * Created by PhpStorm.
 * User: dangenendt
 * Date: 06.10.18
 * Time: 18:34
 */

namespace HetznerNotify\Filter\Comparator;


class EqualComparator implements ComparatorInterface
{
    /**
     * @param $valueToCompare
     * @param $serverValue
     * @return bool
     */
    public function compare($valueToCompare, $serverValue)
    {
        if ($serverValue == $valueToCompare) {
            return true;
        }

        return false;
    }
}