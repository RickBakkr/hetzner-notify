<?php
/**
 * Created by PhpStorm.
 * User: dangenendt
 * Date: 06.10.18
 * Time: 18:35
 */

namespace HetznerNotify\Filter\Comparator;

class ComparatorFactory
{
    /**
     * static factory for comparator objects
     * @param $operator
     * @return GreaterOrEqualComparator|LessOrEqualComparator|null
     */
    public static function getComparatorByOperator($operator)
    {
        $comparator = null;
        switch ($operator) {
            case '>=':
                $comparator = new GreaterOrEqualComparator();
                break;
            case '<=':
                $comparator = new LessOrEqualComparator();
                break;
            case '==':
                $comparator = new EqualComparator();
                break;
            default:
                //default comparator if nothing is set in config
                $comparator = new GreaterOrEqualComparator();
                break;
        }

        return $comparator;
    }
}