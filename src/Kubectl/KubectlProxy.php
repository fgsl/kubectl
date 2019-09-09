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
        $response = self::getResponse(KubernetesNamespace::getGetCommand($namespace, $object));
        $instance = self::getObject($object, $namespace, $response, KubernetesNamespace::class);
        if (is_object($instance)) {
            return $instance;
        }
        $tokens = explode("\n", $response);
        $tokens[1] = preg_replace('/\s+/', ';', $tokens[1]); // replace whitespaces with ;
        $tokens = explode(";", $tokens[1]);
        $instance = new KubernetesNamespace($namespace, $tokens[1], $tokens[2]);
        return $instance;
    }

    public static function getResourceQuota(string $namespace, bool $object = false): KubernetesResourceQuota
    {
        $response = self::getResponse(KubernetesResourceQuota::getGetCommand($namespace, $object));
        $instance = self::getObject($object, $namespace, $response, KubernetesResourceQuota::class);
        if (is_object($instance)) {
            return $instance;
        }
        $tokens = explode("\n", $response);
        $tokens[1] = preg_replace('/\s+/', ';', $tokens[1]); // replace whitespaces with ;
        $tokens = explode(";", $tokens[1]);
        $instance = new KubernetesResourceQuota($namespace, $tokens[1]);
        return $instance;
    }

    public static function getPods(string $namespace, bool $object = false, bool $showLabels = false): KubernetesPods
    {
        $showLabels = $object ? false : $showLabels;
        $response = self::getResponse(KubernetesPods::getGetCommand($namespace, $object, $showLabels));
        $instance = self::getObject($object, $namespace, $response, KubernetesPods::class);
        if (is_object($instance)) {
            return $instance;
        }        
        $tokens = explode("\n", $response);
        $pods = self::getPodsFromTokens($tokens, $showLabels);
        $instance = new KubernetesPods($namespace, $pods);
        return $instance;
    }
    
    public static function getVolumes(string $module, bool $object = false): KubernetesVolumes
    {
        $response = self::getResponse(KubernetesVolumes::getGetCommand($module, $object));
        $instance = self::getObject($object, $module, $response, KubernetesVolumes::class);
        if (is_object($instance)) {
            return $instance;
        }
        $tokens = explode("\n", $response);
        $pods = self::getPodsFromTokens($tokens, false);
        $instance = new KubernetesVolumes($module, $pods);
        return $instance;
    }
    
    public static function getReplicaSets(string $namespace, bool $object = false): KubernetesReplicaSets
    {
        $response = self::getResponse(KubernetesReplicaSets::getGetCommand($namespace, $object));
        $instance = self::getObject($object, $namespace, $response, KubernetesReplicaSets::class);
        if (is_object($instance)) {
            return $instance;
        }
        $tokens = explode("\n", $response);
        $replicaSets = self::getReplicaSetsFromTokens($tokens);
        $instance = new KubernetesReplicaSets($namespace, $replicaSets);
        return $instance;
    }    

    protected static function getResponse(string $command): string
    {
        $response = shell_exec($command);
        if (is_null($response)) {
            throw new KubectlException();
        }
        return $response;
    }
    
    protected static function getObject(bool $object,string $namespace, string $response, string $class)
    {
        $instance = null;
        if ($object) {
            $response = yaml_parse($response);
            $instance = new $class($namespace);
            $instance->setProperties($response);
        }
        return $instance;
    }
    
    protected static function getPodsFromTokens(array $tokens, bool $showLabels): array
    {
        unset($tokens[0]);
        $pods = [];
        foreach ($tokens as $token) {
            if (trim($token) == '')
                break;
                $column = preg_replace('/\s+/', ';', $token); // replace whitespaces with ;
                $fields = explode(';', $column);
                $pods[$fields[0]]['ready'] = $fields[1];
                $pods[$fields[0]]['status'] = $fields[2];
                $pods[$fields[0]]['restarts'] = $fields[3];
                $pods[$fields[0]]['age'] = $fields[4];
                if ($showLabels) {
                    $pods[$fields[0]]['labels'] = $fields[5];
                }
        }
        return $pods;
    }
    
    protected static function getReplicaSetsFromTokens(array $tokens): array
    {
        unset($tokens[0]);
        $replicasSets = [];
        foreach ($tokens as $token) {
            if (trim($token) == '')
                break;
                $column = preg_replace('/\s+/', ';', $token); // replace whitespaces with ;
                $fields = explode(';', $column);
                $replicasSets[$fields[0]]['desired'] = $fields[1];
                $replicasSets[$fields[0]]['current'] = $fields[2];
                $replicasSets[$fields[0]]['ready'] = $fields[3];
                $replicasSets[$fields[0]]['age'] = $fields[4];
        }
        return $replicasSets;
    }
}