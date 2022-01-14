<?php

namespace App\Service;



class StringCalculatorService
{
    /**
     * This function will take a string of comma delimited numbers and return a sum of the numbers.
     * 
     * @param string $numbers
     * @return int
     */
    public function add(String $numbers): int
    {

        if (true === empty($numbers)) {
            return 0;
        }

        $numbers = $this->scrub($numbers);
        $numbers = explode(',', $numbers);
        $sum = 0;

        foreach ($numbers as $number) {
            $sum += $number;
        }

        return $sum;
    }

    /**
     * This function take a string of numbers and scrub the input of newlines (\n).
     * 
     * @param string $numbers
     * @return String
     */
    public function scrub(String $numbers): String
    {
        $numbers = trim(preg_replace('/\n/', '', $numbers));
        return $numbers;
    }

}
