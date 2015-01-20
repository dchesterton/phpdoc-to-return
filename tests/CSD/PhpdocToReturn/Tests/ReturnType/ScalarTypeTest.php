<?php
namespace CSD\PhpdocToReturn\Tests\ReturnType;

use CSD\PhpdocToReturn\ReturnType\ScalarType;

class ScalarTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testScalarType()
    {
        $type = new ScalarType('scalar');

        $this->assertFalse($type->getDeclaration(false));
        $this->assertEquals('scalar', $type->getDeclaration(true));
        $this->assertTrue($type->isDocCommentRedundant());
    }
}