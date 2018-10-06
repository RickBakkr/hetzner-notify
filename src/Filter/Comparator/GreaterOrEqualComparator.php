<?php
namespace HetznerNotify\Filter\Comparator;


class GreaterOrEqualComparator implements ComparatorInterface
{
    /**
     * @param $valueToCompare
     * @param $serverValue
     * @return bool
     */
    public function compare($valueToCompare, $serverValue)
    {
        if ($serverValue >= $valueToCompare) {
            return true;
        }

        return false;
    }
}