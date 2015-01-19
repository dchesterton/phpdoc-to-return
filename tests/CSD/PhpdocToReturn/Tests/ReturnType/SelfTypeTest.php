<?php
namespace CSD\PhpdocToReturn\Tests\ReturnType;

use CSD\PhpdocToReturn\ReturnType\SelfType;

class SelfTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testSelfType()
    {
        $type = new SelfType;
        $this->assertEquals('self', $type->getDeclaration());
        $this->assertTrue($type->isDocCommentRedundant());
    }
}