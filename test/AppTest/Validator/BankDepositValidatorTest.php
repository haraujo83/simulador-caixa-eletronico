<?php

declare(strict_types=1);

namespace AppTest\Validator;

use App\Validator\BankDepositValidator;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class BankDepositValidatorTest extends TestCase
{
    /**
     * @dataProvider validValuesProvider
     */
    public function testValidValues($value): void
    {
        $validator = new BankDepositValidator();

        $isValid = $validator->isValid($value);

        self::assertTrue($isValid);
    }

    /**
     * @return int[]
     */
    public function validValuesProvider(): array
    {
        return [
            [10],
            [20],
            [50],
            [70],
            [100],
            [250],
        ];
    }

    /**
     * @dataProvider invalidValuesProvider
     */
    public function testInvalidValues($value): void
    {
        $validator = new BankDepositValidator();

        $isValid = $validator->isValid($value);

        self::assertFalse($isValid);
    }

    /**
     * @return int[]
     */
    public function invalidValuesProvider(): array
    {
        return [
            [-1],
            [0],
            [1.2],
            [0.0],
            [15.50],
            [25.73],
        ];
    }
}
