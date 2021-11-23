<?php
declare(strict_types=1);

namespace App\TableGateway;

use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Db\TableGateway\Feature;

/**
 *
 */
class UsersTableGateway extends AbstractTableGateway
{
    /**
     *
     */
    public function __construct()
    {
        $this->table      = 'users';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * @param string|null $query
     * @return array
     */
    public function search(?string $query): array
    {
        return $this->select(function (Select $select) use ($query) {
            if ($query !== null && $query !== '') {
                $where = $select->where;

                $where->like('name', "%$query%");
            }
            $select->order('name');
        })->toArray();
    }
}
