<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Filter\StringToLower;
use Laminas\Filter\StringTrim;
use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;

/**
 * Campos para buscar usuários
 */
class UsersSearchInputFilter extends InputFilter
{
    public function __construct()
    {
        $input = (new Input())->setRequired(false);
        $input->getFilterChain()->attach(new StringTrim())->attach(new StringToLower());
        $this->add($input, 'q');
    }
}
