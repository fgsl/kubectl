<?php
use Fgsl\Kubectl\KubectlProxy;

/**
 * PHP Kubectl Abstraction Layer
 *
 * @author Flávio Gomes da Silva Lisboa <flavio.lisboa@fgsl.eti.br>
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */
require 'bootstrap.php';

if (! KubectlProxy::isInstalled()) {
    throw new \Exception('Kubectl is not installed!');
}

if (! isset($argv[1])) {
    echo "Provide a namespace!\n";
    exit();
}
$namespace = $argv[1];

$sets = [
    "\nTesting default response...\n" => false,
    "\nTesting object response...\n" => true
];

foreach ($sets as $message => $object) {
    echo $message;
    echo "\nTesting namespace...\n";
    echo KubectlProxy::getNamespace($namespace, $object);

    echo "\nTesting resource quota...\n";
    echo KubectlProxy::getResourceQuota($namespace, $object);

    echo "\nTesting pods...\n";
    echo KubectlProxy::getPods($namespace, $object);
}

