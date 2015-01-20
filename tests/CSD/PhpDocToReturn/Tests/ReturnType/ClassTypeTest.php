<?php
namespace CSD\PhpDocToReturn\Tests\ReturnType;

use CSD\PhpDocToReturn\ReturnType\ClassType;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class ClassTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testClassType()
    {
        $type = new ClassType('\DateTime');
        $this->assertEquals('\DateTime', $type->getDeclaration(false));
        $this->assertTrue($type->isDocCommentRedundant());
    }
}
