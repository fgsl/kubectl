<?php
/**
 * PHP Kubectl Abstraction Layer
 *
 * @author Flávio Gomes da Silva Lisboa <flavio.lisboa@fgsl.eti.br>
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */
use Fgsl\Kubectl\KubectlProxy;
use Fgsl\Kubectl\KubernetesPods;
use Fgsl\Kubectl\KubernetesReplicaSets;
//========================= MAIN BLOCK ========================================
require 'bootstrap.php';

if (! KubectlProxy::isInstalled()) {
    throw new \Exception('Kubectl is not installed!');
}

if (! isset($argv[1])) {
    echo "Provide a namespace!\n";
    exit();
}

if (! isset($argv[2])) {
    echo "Provide a module!\n";
    exit();
}

$namespace = $argv[1];
$module = $argv[2];

echo str_repeat('=', 79) . "\n";
echo str_pad('INTEGRATED TESTS FOR PHP KUBECTL',79,'=',STR_PAD_BOTH). "\n";;
echo str_repeat('=', 79). "\n";;

$sets = [
    "\nTesting default response...\n" => false,
    "\nTesting object response...\n" => true
];

$exceptions = [];

foreach ($sets as $message => $object) {
    echo "\n$message\n";
    
    tryCallMethod('Testing namespace', KubectlProxy::getNamespace($namespace, $object), $exceptions);

    tryCallMethod('Testing resource quota', KubectlProxy::getResourceQuota($namespace, $object), $exceptions);
        
    tryCallMethod('Testing pods', KubectlProxy::getPods($namespace, $object), $exceptions);
    
    tryCallMethod('Testing replicas sets', KubectlProxy::getReplicaSets($namespace, $module, $object), $exceptions);
}

tryCallMethod('Testing volumes',KubectlProxy::getVolumes($module, true),$exceptions);
tryCallMethod('Testing volumes',KubectlProxy::getVolumes($module, false),$exceptions);

tryCallMethod('Testing module replica set',KubernetesReplicaSets::describe($namespace, $module),$exceptions);

$yamlPodDefinition = __DIR__ . '/yamls/pod-nginx.yaml';
if (file_exists($yamlPodDefinition)){
    $yamlContent = file_get_contents($yamlPodDefinition);
    $yamlContent = str_replace('$USER' , getenv('USER'), $yamlContent);
    tryCallMethod('Testing pod creation', KubernetesPods::create($yamlContent), $exceptions);
    $yamlObject = yaml_parse($yamlContent);
    $module = $yamlObject['metadata']['labels']['run'];
    tryCallMethod('Testing pod destruction', KubernetesPods::delete($namespace,$module), $exceptions);
} else {
    echo "\nWARNING\n";
    echo "You must to provide a file called pod-nginx.yaml for testing pod creation\n";
}

if (count($exceptions)>0) {
    echo "\nExceptions ocurred:\n";
    return true;
} else {
    echo "\nTests had runned no Exceptions.\n";    
}
foreach($exceptions as $exception){
    echo "$exception\n";
}
return false;
//========================= MAIN BLOCK ========================================
//========================= FUNCTIONS =========================================
function tryCallMethod($message, $command, $exceptions)
{
    try {
        echo "\n$message...\n";
        echo $command;
    } catch (\Exception $e) {
        $exceptions[] = $e->getMessage();
    }
}
//========================= FUNCTIONS =========================================