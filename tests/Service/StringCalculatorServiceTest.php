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

        $this->assertEquals($expectedResult, $result);
    }

    public function testReturnTypeInteger(): void
    {
        $inputString = '1,1';
        $result = $this->stringCalculatorService->add($inputString);

        $this->assertIsInt($result);
    }

    public function testScrubNewlines(): void
    {
        $inputString = "1\n,\n2,3";
        $expectedResult = '1,2,3';
        $result = $this->stringCalculatorService->scrub($inputString);

        $this->assertEquals($expectedResult, $result);
    }

    public function testControlCodeFound(): void
    {
        $inputString = "//;\n1;3;4";
        $result = $this->stringCalculatorService->hasControlCode($inputString);

        $this->assertTrue($result);
    }

    public function testControlCodeNotFound(): void
    {
        $inputString = "1;3;4";
        $result = $this->stringCalculatorService->hasControlCode($inputString);

        $this->assertFalse($result);
    }

    public function testGetDelimetersFromControlCode(): void
    {
        $inputString = "//;\n1;3;4";
        $expectedResult = ';';
        $result = $this->stringCalculatorService->getDelimeter($inputString);

        $this->assertEquals($expectedResult, $result);
    }

    public function testGetDelimetersWithNoControlCode(): void
    {
        $inputString = "1,3,4";
        $expectedResult = ',';
        $result = $this->stringCalculatorService->getDelimeter($inputString);

        $this->assertEquals($expectedResult, $result);
    }

    public function testGetNumbersFromDataWithControlCode(): void
    {
        $inputString = "//;\n1;3;4";
        $expectedResult = '1;3;4';
        $result = $this->stringCalculatorService->getNumbers($inputString);

        $this->assertEquals($expectedResult, $result);
    }

    public function testGetNumbersFromDataWithoutControlCode(): void
    {
        $inputString = "1,3,4";
        $expectedResult = '1,3,4';
        $result = $this->stringCalculatorService->getNumbers($inputString);

        $this->assertEquals($expectedResult, $result);
    }

    public function testNegativeNumberThrowsException(): void
    {
        $inputString = "1,-3,4";
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Negatives not allowed: -3');
        $this->stringCalculatorService->add($inputString);
    }

    /**
     * @dataProvider additionProvider
     */
    public function testInputCases($inputString, $expectedResult): void
    {
        $result = $this->stringCalculatorService->add($inputString);

        $this->assertEquals($expectedResult, $result);
    }

    public function additionProvider(): array
    {
        return [
            ['', 0],
            ["//*\n", 0],
            ['1', 1],
            ['1,2,5', 8],
            ["1\n,2,3", 6],
            ["1,\n2,4", 7],
            ["//;\n1;3;4", 8],
            ["//;\n1;\n3;4", 8],
            ["//$\n1$2$3", 6],
            ["//@\n2@3@8", 13],
            ["2,1001", 2],
            ["2,1001,3,4", 9],
            ["//@\n2@1001", 2],
            ["//***\n1***2***3", 6],
            ["//$,@\n1$2@3", 6],
            ["//$,@\n1@2$3@4", 10],
            ["//$$,@,*****\n1$$2@2$$4*****1", 10],
        ];
    }
}
