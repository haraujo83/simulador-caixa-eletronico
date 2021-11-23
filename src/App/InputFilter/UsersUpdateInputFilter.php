<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\InputFilter\InputFilter;

/**
 * Classe com campos e seus validadores para alteração de usuário
 */
class UsersUpdateInputFilter extends InputFilter
{
    use UsersRowInputFilterTrait;

    /**
     * @param int|null $idUser ID do usuário
     */
    public function __construct(?int $idUser)
    {
        $this->add($this->idInput($idUser), 'id');

        $this->add($this->nameInput(), 'name');

        $this->add($this->birthDateInput(), 'birth_date');

        $this->add($this->cpfInput(), 'cpf');

        $this->add($this->idStatusInput(), 'id_status');
    }
}
