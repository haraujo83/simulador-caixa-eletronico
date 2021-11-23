<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\InputFilter\InputFilter;

/**
 * Classe com campos e seus validadores para exclusão de usuário
 */
class UsersDeleteInputFilter extends InputFilter
{
    use UsersRowInputFilterTrait;

    public function __construct()
    {
        $this->add($this->idInput(), 'id');
    }
}
