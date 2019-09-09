<?php
/**
 * PHP Kubectl Abstraction Layer
 * @author Flávio Gomes da Silva Lisboa <flavio.lisboa@fgsl.eti.br>
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */
declare(strict_types = 1);
namespace Fgsl\Kubectl;

class KubernetesNamespace extends AbstractKubernetesObject
{

    private $status;

    private $age;

    public static function getGetCommand(string $namespace, bool $object): string
    {
        return 'kubectl get namespace ' . $namespace . ($object ? ' -o yaml' : '');
    }

    public function __construct(string $namespace, string $status = null, string $age = null)
    {
        $this->namespace = $namespace;
        $this->status = $status;
        $this->age = $age;
    }

    public function __toString()
    {
        if ($this->properties == []) {
            return 'Namespace ' . $this->namespace . ' is ' . $this->status . ' and has ' . $this->age;
        }
        $response = $this->arrayToStringRecursive($this->properties);
        return $response;
    }

    public function setProperties(array $properties): void
    {
        $this->status = $properties['status']['phase'];
        parent::setProperties($properties);
    }

    public function getAnnotations(): string
    {
        return $this->getMetadata('annotations');
    }

    public function getLabels(): string
    {
        return $this->getMetadata('labels');
    }
}