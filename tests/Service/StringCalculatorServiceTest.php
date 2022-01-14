<?php

namespace App\Tests\Service;

use App\Service\StringCalculatorService;
use PHPUnit\Framework\TestCase;

class StringCalculatorServiceTest extends TestCase
{

    private StringCalculatorService $stringCalculatorService;

    protected function setUp(): void
    {
        $this->stringCalculatorService = new StringCalculatorService();
    }

    public function testEmptyStringReturnValue(): void
    {
        $inputString = '';
        $expectedResult = 0;
        $result = $this->stringCalculatorService->add($inputString);

        $this->assertEquals($result, $expectedResult);
    }

    public function testReturnTypeInteger(): void
    {
        $inputString = '1,1';
        $expectedResult = 0;
        $result = $this->stringCalculatorService->add($inputString);

        $this->assertIsInt($result);
    }

    public function testScrubNewlines(): void
    {
        $inputString = "1\n,\n2,3";
        $expectedResult = '1,2,3';
        $result = $this->stringCalculatorService->scrub($inputString);

        $this->assertEquals($result, $expectedResult);
    }

    /**
     * @dataProvider additionProvider
     */
    public function testInputCases($inputString, $expectedResult): void
    {
        $result = $this->stringCalculatorService->add($inputString);

        $this->assertEquals($result, $expectedResult);
    }

    public function additionProvider(): array
    {
        return [
            ['', 0],
            ['1', 1],
            ['1,2,5', 8],
            ["1\n,2,3", 6],
            ["1,\n2,4", 7]
        ];
    }
}
