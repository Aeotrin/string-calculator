<?php

namespace App\Service;

class StringCalculatorService
{
    protected const CONTROL_CODE = '//';

    /**
     * Take a string of comma delimited numbers and return a sum of the numbers.
     * 
     * @param string $numbers
     * @return int
     */
    public function add(String $numbers): int
    {

        if (true === empty($numbers)) {
            return 0;
        }
        
        $delimeter = $this->getDelimeter($numbers);
        $numbers = $this->getNumbers($numbers);
        $numbers = $this->scrub($numbers);

        $numbers = explode($delimeter, $numbers);
        $sum = 0;

        foreach ($numbers as $number) {
            if ($number < 0) {
                throw new \Exception('Negatives not allowed: ' . $number);
            }
            
            $sum += $number;
        }

        return $sum;
    }

    /**
     * Take a string of numbers and scrub the input of newlines (\n).
     * 
     * @param string $numbers
     * @return String
     */
    public function scrub(String $numbers): String
    {
        $numbers = trim(preg_replace('/\n/', '', $numbers));
        return $numbers;
    }

    /**
     * Check to see if given string contains the control code.
     * 
     * @param string $numbers
     * @return bool
     */
    public function hasControlCode(String $numbers): bool
    {
        $controlCodePosition = strpos($numbers, $this::CONTROL_CODE);

        if (false === $controlCodePosition) {
            return false;
        }

        return true;
    }

    /**
     * Extract the delimeter from the control code of the provided string.
     * 
     * @param string $numbers
     * @return String
     */
    public function getDelimeter(String $numbers): String
    {
        if (false === $this->hasControlCode($numbers)) {
            return ',';
        }

        $input = preg_split('/\R/m', $numbers, 2, PREG_SPLIT_NO_EMPTY);
        $controlCodeStart = strpos($numbers, self::CONTROL_CODE);
        $delimeter = substr($input[0], strlen($controlCodeStart) + 1);

        return $delimeter;
    }
    
    /**
     * Extract the list of numbers from the provided string.
     * 
     * @param string $numbers
     * @return String
     */
    public function getNumbers(String $numbers): String
    {
        if (false === $this->hasControlCode($numbers)) {
            return $numbers;
        }
        
        $input = preg_split('/\R/m', $numbers, 2, PREG_SPLIT_NO_EMPTY);

        return $input[1];
    }

}
