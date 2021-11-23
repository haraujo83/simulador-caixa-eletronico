<?php

declare(strict_types=1);

namespace App\TableGateway;

use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Db\TableGateway\Feature;

/**
 * Classe para manipular tabela de usuários
 */
class UsersTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table      = 'users';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * Retorna lista de usuários, com opção de filtrar por parte do nome.
     *
     * @param string|null $query Termo da busca
     * @return array
     */
    public function search(?string $query): array
    {
        return $this->select(function (Select $select) use ($query) {
            $select->join('user_statuses', 'user_statuses.id = users.id_status', []);
            $select->columns(
                [
                    'id',
                    'name',
                    'birth_date' => new Expression('date_format(birth_date, ?)', '%d/%m/%Y'),
                    'cpf',
                    'status' => new Expression('user_statuses.description'),
                ]
            );
            if ($query !== null && $query !== '') {
                $where = $select->where;
                $where->like('name', "%$query%");
                $where->equalTo('deleted', 0);
            }
            $select->order('name');
        })->toArray();
    }
}
