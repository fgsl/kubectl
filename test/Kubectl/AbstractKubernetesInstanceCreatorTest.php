<?php
namespace Fgsl\Test\Kubectl;

use Fgsl\Kubectl\KubernetesObjectInterface;
use PHPUnit\Framework\TestCase;

abstract class AbstractKubernetesInstanceCreatorTest extends TestCase
{
    /**
     * @var KubernetesObjectInterface
     */
    protected $instance = null;
    
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