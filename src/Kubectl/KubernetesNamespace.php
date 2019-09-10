<?php
/**
 * PHP Kubectl Abstraction Layer
 * @author Flávio Gomes da Silva Lisboa <flavio.lisboa@fgsl.eti.br>
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */
declare(strict_types = 1);
namespace Fgsl\Kubectl;

/**
 * @package Fgsl
 * @subpackage Kubectl
 */
class KubernetesNamespace extends AbstractKubernetesObject
{

    /** @var string */
    private $status;

    /** @var string */
    private $age;

    /**
     * @param string $namespace
     * @param bool $object
     * @return string
     */
    public static function getGetCommand(string $namespace, bool $object): string
    {
        return 'kubectl get namespace ' . $namespace . ($object ? ' -o yaml' : '');
    }

    /**
     * @param string $namespace
     * @param string $status
     * @param string $age
     */
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

    /**
     * {@inheritDoc}
     * @see \Fgsl\Kubectl\AbstractKubernetesObject::setProperties()
     */
    public function setProperties(array $properties): void
    {
        $this->status = $properties['status']['phase'];
        parent::setProperties($properties);
    }

    /**
     * @return string
     */
    public function getAnnotations(): string
    {
        return $this->getMetadata('annotations');
    }

    /**
     * @return string
     */
    public function getLabels(): string
    {
        return $this->getMetadata('labels');
    }
}