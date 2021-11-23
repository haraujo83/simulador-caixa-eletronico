<?php
declare(strict_types=1);

namespace AppTest\Validator;

use App\Validator\CpfValidator;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class CpfValidatorTest extends TestCase
{
    /**
     * @dataProvider validValuesProvider
     */
    public function testValidValues($value): void
    {
        $validator = new CpfValidator();

        $isValid = $validator->isValid($value);

        self::assertTrue($isValid);
    }

    /**
     * @return int[]
     */
    public function validValuesProvider(): array
    {
        return [
            ['226.977.958-47'],
            ['22697795847'],
        ];
    }

    /**
     * @dataProvider invalidValuesProvider
     */
    public function testInvalidValues($value): void
    {
        $validator = new CpfValidator();

        $isValid = $validator->isValid($value);

        self::assertFalse($isValid);
    }

    /**
     * @return int[]
     */
    public function invalidValuesProvider(): array
    {
        return [
            ['1'],
            ['123.456.789-00'],
            ['111.111.111-11'],
            ['11111111111'],
        ];
    }
}
