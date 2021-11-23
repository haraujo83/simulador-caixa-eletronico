<?php

declare(strict_types=1);

namespace App\InputFilter;

use App\Input\CpfInput;
use App\Input\DateInput;
use App\TableGateway\UsersTableGateway;
use App\TableGateway\UserStatusesTableGateway;
use Laminas\Filter\StringTrim;
use Laminas\InputFilter\Input;
use Laminas\Validator\Date;
use Laminas\Validator\Db\NoRecordExists;
use Laminas\Validator\Db\RecordExists;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

/**
 * Classe com campos e seus validadores para inclusão de usuário
 */
trait UsersRowInputFilterTrait
{
    private ?int $idUser;

    protected function idInput(?int $idUser = null): Input
    {
        $this->idUser = $idUser;

        $input = new Input();
        $input->getFilterChain()->attach(new StringTrim());
        $input->getValidatorChain()->attach(new NotEmpty([
            'messages' => ['isEmpty' => 'O ID do registro não foi informado'],
        ]), true);

        return $input;
    }

    protected function nameInput(): Input
    {
        $input = new Input();
        $input->getFilterChain()->attach(new StringTrim());
        $input->getValidatorChain()->attach(new NotEmpty([
            'messages' => ['isEmpty' => 'O nome não foi informado'],
        ]), true)
            ->attach(new StringLength(
                [
                    'max'      => 255,
                    'messages' => ['stringLengthTooLong' => 'O nome ultrapassa o limite de %max% caracteres'],
                ]
            ), true);

        return $input;
    }

    protected function birthDateInput(): DateInput
    {
        $input = new DateInput();

        $input->getValidatorChain()->attach(
            new NotEmpty(['messages' => ['isEmpty' => 'A data de nascimento não foi informada']]),
            true
        )
            ->attach(new Date(
                [
                    'format' => 'Y-m-d',
                    'messages'
                    => ['dateInvalidDate' => 'A data de nascimento informada é inválida'],
                ]
            ));

        return $input;
    }

    protected function cpfInput(): CpfInput
    {
        $usersTableGateway = new UsersTableGateway();

        $input = new CpfInput();

        $options = [
            'table'    => $usersTableGateway->getTable(),
            'field'    => 'cpf',
            'adapter'  => $usersTableGateway->getAdapter(),
            'messages' => ['recordFound' => 'O CPF informado já está cadastrado'],
        ];
        if (isset($this->idUser)) {
            $options['exclude'] = [
                'field' => 'id',
                'value' => $this->idUser,
            ];
        }

        $input->getValidatorChain()
            ->attach(new NotEmpty(
                [
                    'messages' => ['isEmpty' => 'O CPF não foi informado'],
                ]
            ), true)
            ->attach(new NoRecordExists($options), true);

        return $input;
    }

    protected function idStatusInput(): Input
    {
        $userStatusesTableGateway = new UserStatusesTableGateway();

        $options = [
            'table'    => $userStatusesTableGateway->getTable(),
            'field'    => 'id',
            'adapter'  => $userStatusesTableGateway->getAdapter(),
            'messages' => ['noRecordFound' => 'O status informado é inválido'],
        ];

        $input = new Input();
        $input->getValidatorChain()
            ->attach(new NotEmpty([
                'messages' => ['isEmpty' => 'O ID do status não foi informado'],
            ]), true)
            ->attach(new RecordExists($options), true);

        return $input;
    }
}
