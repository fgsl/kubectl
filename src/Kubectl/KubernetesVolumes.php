<?php
/**
 * PHP Kubectl Abstraction Layer
 * @author Flávio Gomes da Silva Lisboa <flavio.lisboa@fgsl.eti.br>
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */
declare(strict_types = 1);
namespace Fgsl\Kubectl;

class KubernetesVolumes extends AbstractKubernetesObject
{

    /** @var string **/
    private $module;

    /** @var array */
    private $pods;

    public static function getGetCommand(string $module, bool $object): string
    {
        return 'kubectl get pods -l run=' . $module . ($object ? ' -o yaml' : '');
    }

    public function __construct(string $module, array $pods = null)
    {
        $this->module = $module;
        $this->pods = $pods;
    }

    public function __toString()
    {
        if ($this->properties == []) {
            $output = 'Module ' . $this->module . "\n";
            foreach ($this->pods as $pod => $values) {
                $output .= "pod=$pod ready={$values['ready']} status={$values['status']} restarts={$values['restarts']} age={$values['age']}\n";
            }
            return $output;
        }
        $response = $this->arrayToStringRecursive($this->properties);
        return $response;
    }
}