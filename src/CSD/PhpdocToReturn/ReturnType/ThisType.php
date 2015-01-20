<?php
namespace CSD\PhpdocToReturn\ReturnType;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
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
