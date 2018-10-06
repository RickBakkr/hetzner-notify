<?php
namespace HetznerNotify\Filter\Comparator;


class GreaterOrEqualComparator implements ComparatorInterface
{
    public function compare($valueToCompare, $serverValue)
    {
        if ($serverValue >= $valueToCompare) {
            return true;
        }

        return false;
    }
}