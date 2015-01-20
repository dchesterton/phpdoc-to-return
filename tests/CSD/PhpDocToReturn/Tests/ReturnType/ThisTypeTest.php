<?php
namespace CSD\PhpDocToReturn\Tests\ReturnType;

use CSD\PhpDocToReturn\ReturnType\ThisType;

class ThisTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testThisType()
    {
        $type = new ThisType;
        $this->assertEquals('self', $type->getDeclaration(false));
        $this->assertFalse($type->isDocCommentRedundant());
    }
}