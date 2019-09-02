<?php
namespace test\Kubectl;

use PHPUnit\Framework\TestCase;
use Fgsl\Kubectl\KubectlProxy;

class KubectlProxyTest extends TestCase
{
    public function testMethods(){
        $methods = get_class_methods(KubectlProxy::class);
        $this->assertTrue(in_array('getNamespace', $methods));
        $this->assertTrue(in_array('getResourceQuota', $methods));
    }
}