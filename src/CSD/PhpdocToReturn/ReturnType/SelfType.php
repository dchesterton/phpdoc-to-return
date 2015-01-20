<?php
namespace CSD\PhpdocToReturn\ReturnType;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 */
class SelfType implements ReturnTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDeclaration($hack)
    {
        return 'self';
    }

    /**
     * {@inheritdoc}
     */
    public function isDocCommentRedundant()
    {
        return true;
    }
}
