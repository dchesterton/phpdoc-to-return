<?php
namespace CSD\PhpdocToReturn\Tests\ReturnType;

use CSD\PhpdocToReturn\ReturnType\ArrayType;

class ArrayTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayTypeWithNoType()
    {
        $type = new ArrayType;
        $this->assertEquals('array', $type->getDeclaration());
        $this->assertNull($type->getType());
        $this->assertTrue($type->isDocCommentRedundant());
    }

    public function testArrayTypeWithType()
    {
        $type = new ArrayType('\DateTime');
        $this->assertEquals('array', $type->getDeclaration());
        $this->assertEquals('\DateTime', $type->getType());
        $this->assertFalse($type->isDocCommentRedundant());
    }
}