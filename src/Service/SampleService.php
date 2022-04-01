<?php

namespace App\Service;

/**
 * Class SampleService
 *
 * @package App\Service
 */
class SampleService
{
    /**
     * @param int $firstNumber
     * @param int $secondNumber
     *
     * @return int
     */
    public function sum(int $firstNumber, int $secondNumber)
    {
        echo 1111;
        return $firstNumber + $secondNumber;
    }
}
