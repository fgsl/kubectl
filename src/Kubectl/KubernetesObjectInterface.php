<?php
/**
 * PHP Kubectl Abstraction Layer
 * @author Flávio Gomes da Silva Lisboa <flavio.lisboa@fgsl.eti.br>
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */
declare(strict_types = 1);
namespace Fgsl\Kubectl;

interface KubernetesObjectInterface
{
    public function getProperty(string $name);
    
    public function getProperties(): array;
    
    public function setProperties(array $properties);
    
    public function __toString();
}