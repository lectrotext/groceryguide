<?php
namespace GroceryGuide\Utils;

class Bitmasks
{

    public static function months($monthbits) {
        $calendar = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthbits =  str_split($monthbits);

        if (count($monthbits) != 12) {
            throw new Exception('number of months passes does not equal twelve.');
        }

        $months = [];
        for ($i = 0; count($monthbits) > $i; $i++) {
            if ($monthbits[$i]) {
                $months[] = $calendar[$i];
            }
        }
        return $months;
    }
}
