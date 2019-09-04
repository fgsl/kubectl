<?php
namespace Fgsl\Test\Kubectl;

use Fgsl\Kubectl\KubernetesResourceQuota;
use PHPUnit\Framework\TestCase;

class KubernetesResourceQuotaTest extends TestCase
{
    public function setUp(): void
    {
        $this->instance = new KubernetesResourceQuota('namespace-test','created-at-test');
    }
    
    public function testCreateInstance()
    {
        $properties = ['metadata' => [
            'annotations' => [
                'fooannotation',
                'baaannotation'
            ],
            'labels' => [
                'foolabel',
                'baalabel'
            ]
        ]];
        $this->instance->setProperties($properties);
        $labels = $this->instance->getProperties()['metadata']['labels'];
        $annotations = $this->instance->getProperties()['metadata']['annotations'];
        $this->assertArrayHasKey('annotations', $this->instance->getProperties()['metadata']);
        $this->assertArrayHasKey('labels', $this->instance->getProperties()['metadata']);
        $this->assertStringContainsString('fooannotation', implode('',$annotations));
        $this->assertStringContainsString('foolabel', implode('',$labels));
    }
}