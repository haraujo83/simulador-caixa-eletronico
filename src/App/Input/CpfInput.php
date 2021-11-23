<?php

declare(strict_types=1);

namespace App\Input;

use App\Validator\CpfValidator;
use Laminas\Filter\Callback;
use Laminas\Filter\Digits;
use Laminas\Filter\StringTrim;
use Laminas\InputFilter\Input;

use function preg_replace;
use function strlen;

/**
 * Classe para dados do tipo CPF
 */
class CpfInput extends Input
{
    /**
     * @param null $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new Callback(function ($value) {
                $value = (new Digits())->filter($value);

                if (strlen($value) === 11) {
                    $value = preg_replace('/^(\d{3})(\d{3})(\d{3})(\d{2})$/', '${1}.${2}.${3}-${4}', $value);
                }

                return $value;
            }));

        $this->getValidatorChain()
            ->attach(new CpfValidator(), true);
    }
}
