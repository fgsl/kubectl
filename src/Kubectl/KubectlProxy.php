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
    
    public static function getResourceQuota(string $namespace, bool $object = false): KubernetesResourceQuota
    {
        $response = shell_exec('kubectl --namespace=' . $namespace .  ' get resourcequota ' . ($object ? $namespace . ' -o yaml' : ''));
        if (is_null($response)) {
            throw new KubectlException();
        }
        if ($object) {
            $response = yaml_parse($response);
            $kubernetesResourceQuota = new KubernetesResourceQuota($namespace);
            $kubernetesResourceQuota->setProperties($response);
            return $kubernetesResourceQuota;
        }
        $tokens = explode("\n", $response);
        $tokens[1] = preg_replace('/\s+/', ';', $tokens[1]); // replace whitespaces with ;
        $tokens = explode(";", $tokens[1]);
        $kubernetesResourceQuota = new KubernetesResourceQuota($namespace, $tokens[1]);
        return $kubernetesResourceQuota;
    }
    
    public static function getPods(string $namespace, bool $object = false, bool $showLabels = false): KubernetesPods
    {
        $showLabels = $object ? false : $showLabels;
        $response = shell_exec('kubectl --namespace=' . $namespace .  ' get pods ' . ($object ? '-o yaml' : '') . ($showLabels ? '--show-labels' : ''));
        if (is_null($response)) {
            throw new KubectlException();
        }
        if ($object) {
            $response = yaml_parse($response);
            $kubernetesPods = new KubernetesPods($namespace);
            $kubernetesPods->setProperties($response);
            return $kubernetesPods;
        }
        $pods = [];
        $tokens = explode("\n", $response);
        unset($tokens[0]);
        foreach ($tokens as $token){
            if (trim($token) == '') break;
            $column = preg_replace('/\s+/', ';',$token); // replace whitespaces with ;
            $fields = explode(';',$column);
            $pods[$fields[0]]['ready'] = $fields[1]; 
            $pods[$fields[0]]['status'] = $fields[2];
            $pods[$fields[0]]['restarts'] = $fields[3];
            $pods[$fields[0]]['age'] = $fields[4];
            if ($showLabels){
                $pods[$fields[0]]['labels'] = $fields[5];
            }
        }        
        $kubernetesPods = new KubernetesPods($namespace, $pods);
        return $kubernetesPods;
    }    
}