<?php
namespace CSD\PhpDocToReturn\ReturnType;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class ThisType implements ReturnTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDeclaration($hack)
    {
        return $hack? 'this': 'self';
    }

    /**
     * {@inheritdoc}
     */
    public function isDocCommentRedundant()
    {
        return false;
    }
}
