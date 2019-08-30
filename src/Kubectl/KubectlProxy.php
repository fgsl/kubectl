<?php
/**
 * PHP Kubectl Abstraction Layer
 * @author Flávio Gomes da Silva Lisboa <flavio.lisboa@fgsl.eti.br>
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */
declare(strict_types = 1);
namespace Fgsl\Kubectl;

class KubectlProxy
{

    const KUBECTL_HEADER_HELP = 'kubectl controls the Kubernetes cluster manager';

    public static function isInstalled()
    {
        $response = shell_exec('kubectl');
        return (strpos($response, self::KUBECTL_HEADER_HELP) !== false);
    }

    public static function getNamespace(string $namespace, bool $object = false): KubernetesNamespace
    {
        $response = shell_exec('kubectl get namespace ' . $namespace . ($object ? ' -o yaml' : ''));
        if (is_null($response)) {
            throw new KubectlException();
        }
        if ($object) {
            $response = yaml_parse($response);
            $kubernetesNamespace = new KubernetesNamespace($namespace, $response['status']['phase']);
            $kubernetesNamespace->setProperties($response);
            return $kubernetesNamespace;
        }
        $tokens = explode("\n", $response);
        $tokens[1] = preg_replace('/\s+/', ';', $tokens[1]); // replace whitespaces with ;
        $tokens = explode(";", $tokens[1]);
        $kubernetesNamespace = new KubernetesNamespace($namespace, $tokens[1], $tokens[2]);
        return $kubernetesNamespace;
    }
}

