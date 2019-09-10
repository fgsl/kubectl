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
class KubernetesPods extends AbstractKubernetesObject
{
    /** @var array */
    private $pods;

    /**
     * @param string $namespace
     * @param bool $object
     * @param bool $showLabels
     * @return string
     */
    public static function getGetCommand(string $namespace, bool $object, bool $showLabels): string
    {
        return 'kubectl --namespace=' . $namespace . ' get pods ' . ($object ? '-o yaml' : '') . ($showLabels ? '--show-labels' : '');
    }

    /**
     * @param string $yaml
     * @return string
     */
    public static function create(string $yaml): string
    {
        $result = shell_exec('echo "' . $yaml . '" | kubectl create -f -');
        return is_null($result) ? 'Command failed' : $result;
    }

    /**
     * @param string $namespace
     * @param string $module
     * @return string
     */
    public static function delete(string $namespace, string $module): string
    {
        $result = shell_exec('kubectl --namespace=' . $namespace . ' delete pods -l run=' . $module);
        return is_null($result) ? 'Command failed' : $result;
    }

    /**
     * @param string $namespace
     * @param array $pods
     */
    public function __construct(string $namespace, array $pods = null)
    {
        $this->namespace = $namespace;
        $this->pods = $pods;
    }

    /**
     * {@inheritDoc}
     * @see \Fgsl\Kubectl\KubernetesObjectInterface::__toString()
     */
    public function __toString()
    {
        if ($this->properties == []) {
            $output = 'Namespace ' . $this->namespace . "\n";
            foreach ($this->pods as $pod => $values) {
                $output .= "pod=$pod ready={$values['ready']} status={$values['status']} restarts={$values['restarts']} age={$values['age']}";
                $output .= isset($values['labels']) ? " labels={$values['labels']}\n" : "\n";
            }
            return $output;
        }
        $response = $this->arrayToStringRecursive($this->properties);
        return $response;
    }
}