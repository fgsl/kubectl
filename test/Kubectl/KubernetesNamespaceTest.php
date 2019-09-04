<?php
namespace Fgsl\Test\Kubectl;

use Fgsl\Kubectl\KubernetesNamespace;
use PHPUnit\Framework\TestCase;

class KubernetesNamespaceTest extends TestCase
{
    /**
     * @var KubernetesNamespace
     */
    protected $instance = null;
    
    
    public function setUp(): void
    {
        $this->instance = new KubernetesNamespace('namespace-test','status-test','age-test');
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
        $labels = $this->instance->getLabels();
        $annotations = $this->instance->getAnnotations();
        $this->assertArrayHasKey('annotations', $this->instance->getProperties()['metadata']);
        $this->assertArrayHasKey('labels', $this->instance->getProperties()['metadata']);
        $this->assertStringContainsString('fooannotation', $annotations);
        $this->assertStringContainsString('foolabel', $labels);
    }
}