<?php
/**
 * PHP Kubectl Abstraction Layer
 * @author Flávio Gomes da Silva Lisboa <flavio.lisboa@fgsl.eti.br>
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */
namespace Fgsl\Test\Kubectl;

use Fgsl\Kubectl\KubernetesPods;
use PHPUnit\Framework\TestCase;

class KubernetesPodsTest extends TestCase
{
    public function setUp(): void
    {
        $this->instance = new KubernetesPods('namespace-test');
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