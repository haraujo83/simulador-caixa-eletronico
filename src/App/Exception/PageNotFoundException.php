<?php
declare(strict_types=1);

namespace App\Exception;

use Fig\Http\Message\StatusCodeInterface;

/**
 *
 */
class PageNotFoundException extends \Exception
{
    /**
     * @var int
     */
    protected $code = StatusCodeInterface::STATUS_NOT_FOUND;

    /**
     * @var string
     */
    protected $message = 'Página não encontrada';
}
