<?php

namespace App\Service;



class StringCalculatorService
{
    public function add(String $numbers)
    {

        if (true === empty($numbers)) {
            return 0;
        }

        $numbers = explode(',', $numbers);
        $sum = 0;
        foreach ($numbers as $number) {
            $sum += $number;
        }
        return $sum;
    }

}
