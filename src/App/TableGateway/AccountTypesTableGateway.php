<?php

declare(strict_types=1);

namespace App\TableGateway;

use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Db\TableGateway\Feature;

/**
 * Classe para manipular tabela de tipos de conta
 */
class AccountTypesTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table      = 'account_types';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }
}
