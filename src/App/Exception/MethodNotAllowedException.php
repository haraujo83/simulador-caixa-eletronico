<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Fig\Http\Message\StatusCodeInterface;

class MethodNotAllowedException extends Exception
{
    /** @var int */
    protected $code = StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED;
/** @var string */
    protected $message = 'Método não permitido';
}
