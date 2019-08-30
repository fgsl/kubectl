<?php
/**
 * PHP Kubectl Abstraction Layer
 * @author Flávio Gomes da Silva Lisboa <flavio.lisboa@fgsl.eti.br>
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */
declare(strict_types = 1);
namespace Fgsl\Kubectl;

class KubernetesNamespace
{

    const TAB = '    ';

    // 4 spaces
    private $namespace;

    private $status;

    private $age;

    private $properties = [];

    public function __construct(string $namespace, string $status, string $age = null)
    {
        $this->namespace = $namespace;
        $this->status = $status;
        $this->age = $age;
    }

    public function getProperty($name)
    {
        return $this->properties[$name];
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    public function __toString()
    {
        if ($this->properties == []) {
            return 'Namespace ' . $this->namespace . ' is ' . $this->status . ' and has ' . $this->age;
        }
        $response = $this->arrayToStringRecursive($this->properties);
        return $response;
    }

    public function getAnnotations()
    {
        return $this->getMetadata('annotations');
    }

    public function getLabels()
    {
        return $this->getMetadata('labels');
    }

    private function getMetadata($metadata)
    {
        if (isset($this->properties['metadata'][$metadata])) {
            $response = $this->arrayToStringRecursive($this->properties['metadata'][$metadata]);
            return $response;
        }
        return "no $metadata found for this namespace";
    }

    private function arrayToStringRecursive(array $map, $tab = false, $tabLevels = 0)
    {
        $response = '';
        foreach ($map as $name => $value) {
            if (is_array($value)) {
                $response .= str_repeat(self::TAB, $tabLevels) . "$name:\n";
                $response .= $this->arrayToStringRecursive($value, true, $tabLevels + 1);
            } else {
                $response .= ($tab ? str_repeat(self::TAB, $tabLevels + 1) : '') . "$name: $value\n";
            }
        }
        return $response;
    }
}