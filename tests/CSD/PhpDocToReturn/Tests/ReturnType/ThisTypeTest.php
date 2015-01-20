<?php
namespace CSD\PhpDocToReturn\Tests\ReturnType;

use CSD\PhpDocToReturn\ReturnType\ThisType;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class ThisTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testThisType()
    {
        $type = new ThisType;
        $this->assertEquals('self', $type->getDeclaration(false));
        $this->assertFalse($type->isDocCommentRedundant());
    }
}
