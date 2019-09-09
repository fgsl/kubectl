<?php
/**
 * PHP Kubectl Abstraction Layer
 * @author Flávio Gomes da Silva Lisboa <flavio.lisboa@fgsl.eti.br>
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */
declare(strict_types = 1);
namespace Fgsl\Kubectl;

abstract class AbstractKubernetesObject implements KubernetesObjectInterface
{
    const TAB = '    ';// 4 spaces
    
    protected $namespace;

    protected $properties = [];

    public function getProperty(string $name)
    {
        return $this->properties[$name];
    }
    
    public function __get($name)
    {
        return $this->properties[$name];
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    protected function getMetadata(string $metadata):string
    {
        if (isset($this->properties['metadata'][$metadata])) {
            $response = $this->arrayToStringRecursive($this->properties['metadata'][$metadata]);
            return $response;
        }
        return "no $metadata found for this namespace";
    }

    protected function arrayToStringRecursive(array $map, bool $tab = false, int $tabLevels = 0):string
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