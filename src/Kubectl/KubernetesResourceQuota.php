<?php
/**
 * PHP Kubectl Abstraction Layer
 * @author Flávio Gomes da Silva Lisboa <flavio.lisboa@fgsl.eti.br>
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */
declare(strict_types = 1);
namespace Fgsl\Kubectl;

class KubernetesResourceQuota extends AbstractKubernetesObject
{
    private $createdAt;

    public function __construct(string $namespace, string $createdAt = null)
    {
        $this->namespace = $namespace;
        $this->createdAt = $createdAt;
    }

    public function __toString()
    {
        if ($this->properties == []) {
            return 'Namespace ' . $this->namespace . ' was created at ' . $this->createdAt;
        }
        $response = $this->arrayToStringRecursive($this->properties);
        return $response;
    }
}