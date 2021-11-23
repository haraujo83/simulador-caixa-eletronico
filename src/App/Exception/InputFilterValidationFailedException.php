<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Fig\Http\Message\StatusCodeInterface;

use function array_merge;
use function current;
use function is_array;

class InputFilterValidationFailedException extends Exception
{
    /** @var int */
    protected $code = StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY;
/** @var string */
    protected $message = 'Encontrados dados inválidos ou ausentes durante validação';
/** @var array */
    protected array $inputErr = [];
/** @var array */
    protected array $vars = [];
/**
 * @param array $inputErr
 * @param array $vars
 * @return InputFilterValidationFailedException
 */
    public static function forInputErr(array $inputErr, array $vars = []): self
    {
        return (new self())->setInputErr($inputErr)->setVars($vars);
    }

    /**
     * @param array $inputErr
     * @return InputFilterValidationFailedException
     */
    public function setInputErr(array $inputErr): self
    {
        $this->inputErr = $inputErr;
        return $this;
    }

    /**
     * @param array $vars
     * @return $this
     */
    public function setVars(array $vars): self
    {
        $this->vars = $vars;
        return $this;
    }

    /**
     * @return array
     */
    public function getAdditional(): array
    {
        $err = current($this->inputErr);
        while (true) {
            if (is_array($err)) {
                $err = current($err);
            } else {
                break;
            }
        }

        return array_merge(['success' => false, 'inputErr' => $this->inputErr], $this->vars);
    }
}
