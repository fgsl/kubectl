<?php
/**
 * PHP Kubectl Abstraction Layer
 * @author Flávio Gomes da Silva Lisboa <flavio.lisboa@fgsl.eti.br>
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */
declare(strict_types = 1);
namespace Fgsl\Kubectl;

class KubernetesReplicaSets extends AbstractKubernetesObject
{
    /** @var array */
    private $replicaSets;

    public static function getGetCommand(string $namespace, bool $object): string
    {
        return 'kubectl --namespace=' . $namespace . ' get rs ' . ($object ? ' -o yaml' : '');
    }

    public static function getDescribeCommand(string $namespace, string $module): string
    {
        return 'kubectl --namespace=' . $namespace . ' describe replicaset -l run=' . $module;
    }
    
    public static function describe(string $namespace, string $module): string
    {
        return shell_exec(self::getDescribeCommand($namespace, $module));
    }    

    public function __construct(string $namespace, array $replicaSets = null)
    {
        $this->namespace = $namespace;
        $this->replicaSets = $replicaSets;
    }

    public function __toString()
    {
        if ($this->properties == []) {
            $output = 'Namespace ' . $this->namespace . "\n";
            foreach ($this->replicaSets as $rs => $values) {
                $output .= "name=$rs desired={$values['desired']} current={$values['current']} ready={$values['ready']} age={$values['age']}\n";
            }
            return $output;
        }
        $response = $this->arrayToStringRecursive($this->properties);
        return $response;
    }
}