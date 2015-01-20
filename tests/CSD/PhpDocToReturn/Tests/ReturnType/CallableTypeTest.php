<?php
namespace CSD\PhpDocToReturn\Tests\ReturnType;

use CSD\PhpDocToReturn\ReturnType\CallableType;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class CallableTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testCallableType()
    {
        $type = new CallableType;
        $this->assertEquals('callable', $type->getDeclaration(false));
        $this->assertTrue($type->isDocCommentRedundant());
    }
}
