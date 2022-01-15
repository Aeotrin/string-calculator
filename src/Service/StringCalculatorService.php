<?php

namespace App\Service;

class StringCalculatorService
{
    protected const DEFAULT_DELIMETER = ',';
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

        if (self::DEFAULT_DELIMETER === $delimeter) {
            $delimeterPattern = '/[' . self::DEFAULT_DELIMETER . ']/';
        } else {
            $delimeters = preg_split( "/,/", $delimeter );
            $delimeterPattern = '/[' . implode('|', $delimeters) . ']/';
        }

        $numbers = preg_split( $delimeterPattern, $numbers, -1, PREG_SPLIT_NO_EMPTY);

        $sum = 0;

        foreach ($numbers as $number) {
            if ($number < 0) {
                throw new \Exception('Negatives not allowed: ' . $number);
            }
            if ($number > 1000) {
                continue;
            }

            $sum += intval($number);
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
        $controlCodePosition = strpos($numbers, self::CONTROL_CODE);

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
            return self::DEFAULT_DELIMETER;
        }

        $input = preg_split('/\R/', $numbers, 2, PREG_SPLIT_NO_EMPTY);
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
        
        $input = preg_split('/\R/', $numbers, 2, PREG_SPLIT_NO_EMPTY);
        if (array_key_exists(1, $input)) {
            $numbers = $this->scrub($input[1]);
        } else {
            $numbers = '';
        }

        return $numbers;
    }

}
