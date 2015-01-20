<?php
namespace CSD\PhpDocToReturn\ReturnType;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class CallableType implements ReturnTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDeclaration($hack)
    {
        return 'callable';
    }

    /**
     * {@inheritdoc}
     */
    public function isDocCommentRedundant()
    {
        return true;
    }
}
