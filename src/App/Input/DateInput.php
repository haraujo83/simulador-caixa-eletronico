<?php

declare(strict_types=1);

namespace App\Input;

use Laminas\Filter\Callback;
use Laminas\Filter\StringTrim;
use Laminas\InputFilter\Input;

use function count;
use function explode;
use function strpos;

/**
 * Classe para dados do tipo data
 */
class DateInput extends Input
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
                if (strpos($value, '/') !== false) {
                    $parts = explode('/', $value);

                    if (count($parts) === 3) {
                        [$dia, $mes, $ano] = $parts;

                        $value = "$ano-$mes-$dia";
                    }
                }

                return $value;
            }));
    }
}
