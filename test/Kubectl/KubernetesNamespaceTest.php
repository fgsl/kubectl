<?php
namespace Fgsl\Test\Kubectl;

use Fgsl\Kubectl\KubernetesNamespace;

class KubernetesNamespaceTest extends AbstractKubernetesInstanceCreatorTest
{
    public function setUp(): void
    {
        $this->instance = new KubernetesNamespace('namespace-test','status-test','age-test');
    }
}