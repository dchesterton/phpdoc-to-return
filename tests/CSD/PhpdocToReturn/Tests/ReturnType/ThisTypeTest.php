<?php
namespace CSD\PhpdocToReturn\Tests\ReturnType;

use CSD\PhpdocToReturn\ReturnType\ThisType;

class ThisTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testThisType()
    {
        $type = new ThisType;
        $this->assertEquals('self', $type->getDeclaration());
        $this->assertFalse($type->isDocCommentRedundant());
    }
}