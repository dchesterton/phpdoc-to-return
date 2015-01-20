<?php
namespace CSD\PhpdocToReturn\ReturnType;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
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
