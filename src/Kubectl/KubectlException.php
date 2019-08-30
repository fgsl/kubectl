<?php
/**
 * PHP Kubectl Abstraction Layer
 * @author Flávio Gomes da Silva Lisboa <flavio.lisboa@fgsl.eti.br>
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */
namespace Fgsl\Kubectl;

class KubectlException extends \Exception
{

    public function __construct($message = null, $code = null, $previous = null)
    {
        parent::__construct('Kubectl error');
    }
}