<?php
namespace CSD\PhpdocToReturn\Tests\ReturnType;

use CSD\PhpdocToReturn\ReturnType\CallableType;

class CallableTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testCallableType()
    {
        $type = new CallableType;
        $this->assertEquals('callable', $type->getDeclaration(false));
        $this->assertTrue($type->isDocCommentRedundant());
    }
}