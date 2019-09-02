<?php
namespace Fgsl\Test\Kubectl;

use Fgsl\Kubectl\KubernetesResourceQuota;

class KubernetesResourceQuotaTest extends AbstractKubernetesInstanceCreatorTest
{
    public function setUp(): void
    {
        $this->instance = new KubernetesResourceQuota('namespace-test','created-at-test');
    }
}