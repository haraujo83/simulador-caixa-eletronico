<?php

declare(strict_types=1);

namespace App\Validator;

use Laminas\Validator\AbstractValidator;

use function array_sum;
use function is_array;

class BankDepositValidator extends AbstractValidator
{
    public const HAS_CENTS    = 'hasCents';
    public const IS_BELOW_ONE = 'isBelowOne';

    /** @var array|string[] */
    protected array $messageTemplates = [
        self::HAS_CENTS    => 'O valor depositado não pode possuir centavos',
        self::IS_BELOW_ONE => 'O valor depositado deve ser maior que 1',
    ];

    /**
     * @param mixed $value
     */
    public function isValid($value): bool
    {
        if (is_array($value)) {
            $value = array_sum($value);
        }

        $value      = (float) $value;
        $valueCheck = (float) (int) $value;

        if ($value < 1) {
            $this->error(self::IS_BELOW_ONE);
            return false;
        }

        if ($value !== $valueCheck) {
            $this->error(self::HAS_CENTS);
            return false;
        }

        return true;
    }
}
