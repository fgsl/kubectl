<?php
/**
 * PHP Kubectl Abstraction Layer 
 * @author Flávio Gomes da Silva Lisboa <flavio.lisboa@fgsl.eti.br>
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */
namespace Fgsl\Kubectl;

/**
 * @package Fgsl
 * @subpackage Kubectl
 */
class KubectlException extends \Exception
{

    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct(string $message = null, int $code = null, \Exception $previous = null)
    {
        parent::__construct('Kubectl error');
    }
}