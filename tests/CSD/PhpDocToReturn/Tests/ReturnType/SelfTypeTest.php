<?php
namespace CSD\PhpDocToReturn\Tests\ReturnType;

use CSD\PhpDocToReturn\ReturnType\SelfType;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class SelfTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testSelfType()
    {
        $type = new SelfType;
        $this->assertEquals('self', $type->getDeclaration(false));
        $this->assertTrue($type->isDocCommentRedundant());
    }
}
