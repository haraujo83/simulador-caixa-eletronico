<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\InputFilter\InputFilter;

/**
 * Classe com campos e seus validadores para inclusão de usuário
 */
class UsersInsertInputFilter extends InputFilter
{
    use UsersRowInputFilterTrait;

    public function __construct()
    {
        $this->add($this->nameInput(), 'name');

        $this->add($this->birthDateInput(), 'birth_date');

        $this->add($this->cpfInput(), 'cpf');

        $this->add($this->idStatusInput(), 'id_status');
    }
}
