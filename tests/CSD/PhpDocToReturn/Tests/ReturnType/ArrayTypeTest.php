<?php
namespace CSD\PhpDocToReturn\Tests\ReturnType;

use CSD\PhpDocToReturn\ReturnType\ArrayType;

class ArrayTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayTypeWithNoType()
    {
        $type = new ArrayType;
        $this->assertEquals('array', $type->getDeclaration(false));
        $this->assertNull($type->getType());
        $this->assertTrue($type->isDocCommentRedundant());
    }

    public function testArrayTypeWithType()
    {
        $type = new ArrayType('\DateTime');
        $this->assertEquals('array', $type->getDeclaration(false));
        $this->assertEquals('\DateTime', $type->getType());
        $this->assertFalse($type->isDocCommentRedundant());
    }
}