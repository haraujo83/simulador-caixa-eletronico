<?php

declare(strict_types=1);

namespace App\Validator;

use Laminas\Validator\AbstractValidator;

use function preg_match;
use function preg_replace;
use function strlen;

/**
 * Classe para validar CPF
 *
 * @link https://dev.to/alexandrefreire/funcao-em-php-para-validar-cpf-3kpd
 */
class CpfValidator extends AbstractValidator
{
    public const NOT_IS_CPF = 'notIsCpf';
/** @var array|string[] */
    protected array $messageTemplates = [self::NOT_IS_CPF => 'O CPF informado é inválido'];
/**
 * @inheritDoc
 */
    public function isValid($value): bool
    {
        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $value);
// Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) !== 11) {
            $this->error(self::NOT_IS_CPF);
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $this->error(self::NOT_IS_CPF);
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ((string) $cpf[$c] !== (string) $d) {
                $this->error(self::NOT_IS_CPF);
                return false;
            }
        }
        return true;
    }
}
