<?php

namespace App\Service;

class ArraySort
{
    public function sortLastsByDatetime(array $tab, int $number): array
    {
        usort($tab, [$this, 'sortByDate']);
        $tab = array_slice($tab, 0, $number);

        return $tab;
    }

    public static function sortByDate($a, $b)
    {
        $t1 = strtotime($a['createdAt']->format('Y-m-d H:i:s'));
        $t2 = strtotime($b['createdAt']->format('Y-m-d H:i:s'));
        return $t2 - $t1;
    }
}
